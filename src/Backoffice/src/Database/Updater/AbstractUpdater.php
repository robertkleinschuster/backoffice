<?php
namespace Backoffice\Database\Updater;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Sql\AbstractSql;
use Laminas\Db\Sql\Ddl\AlterTable;
use Laminas\Db\Sql\Ddl\Column\Column;
use Laminas\Db\Sql\Ddl\CreateTable;
use Laminas\Db\Sql\Sql;
use Mezzio\Mvc\Helper\ValidationHelperAwareInterface;
use Mezzio\Mvc\Helper\ValidationHelperAwareTrait;

class AbstractUpdater implements ValidationHelperAwareInterface, AdapterAwareInterface
{
    use ValidationHelperAwareTrait;
    use AdapterAwareTrait;

    public const MODE_PREVIEW = 'preview';
    public const MODE_EXECUTE = 'execute';

    private const PREFIX_UPDATE = 'update';

    /**
     * @var string
     */
    private $mode;

    protected $metadata;

    /**
     * @var array
     */
    protected $existingTableList;


    /**
     * SchemaUpdater constructor.
     * @param $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->metadata = \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($this->adapter);
        $this->existingTableList = $this->metadata->getTableNames($adapter->getCurrentSchema());

    }



    /**
    * @return string
    */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
    * @param string $mode
    *
    * @return $this
    */
    public function setMode(string $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasMode(): bool
    {
        return $this->mode !== null;
    }

    /**
     * @return bool
     */
    public function isPreview(): bool
    {
        return $this->hasMode() && $this->getMode() == self::MODE_PREVIEW;
    }

    /**
     * @return bool
     */
    public function isExecute(): bool
    {
        return $this->hasMode() && $this->getMode() == self::MODE_EXECUTE;
    }

    /**
     * @return array
     */
    public function getUpdateMethodList(): array
    {
        $methods = [];
        foreach (get_class_methods(static::class) as $method) {
            if (strpos($method, self::PREFIX_UPDATE) === 0) {
                $methods[] = $method;
            }
        }
        return $methods;
    }

    /**
     * @return array
     */
    public function getPreviewMap(): array
    {
        $this->setMode(self::MODE_PREVIEW);
        $resultMap = [];
        foreach ($this->getUpdateMethodList() as $method) {
            $resultMap[$method] = $this->executeMethod($method);
        }
        return $resultMap;
    }

    public function execute(array $methods): array
    {
        $this->setMode(self::MODE_EXECUTE);
        $methodList = $this->getUpdateMethodList();
        $resultMap = [];
        foreach ($methods as $method) {
            if (in_array($method, $methodList)) {
                $resultMap[$method] = $this->executeMethod($method);
            }
        }
        return $resultMap;
    }

    /**
     * @param string $method
     */
    protected function executeMethod(string $method)
    {
        try {
            return $this->{$method}();
        } catch (\Throwable $ex) {
            $this->getValidationHelper()->addError($method, $ex->getMessage());
            return $ex->getMessage();
        }

    }

    protected function query(AbstractSql $statement)
    {
        $sql = new Sql($this->adapter);
        $result = '';
        if ($this->isExecute()) {
            $result = $this->adapter->query(
                $sql->buildSqlString($statement, $this->adapter),
                Adapter::QUERY_MODE_EXECUTE
            );
        }
        if ($this->isPreview()) {
            $result = str_replace(PHP_EOL, '<br>',  $sql->buildSqlString($statement, $this->adapter));
        }
        return $result;
    }

    /**
     * @param string $table
     * @param string $column
     * @return array
     */
    protected function getKeyList(string $table, string $column)
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select($table);
        $select->columns([$column]);
        $result = $this->adapter->query(
            $sql->buildSqlString($select, $this->adapter),
            Adapter::QUERY_MODE_EXECUTE
        );
        return array_column($result->toArray(), $column);
    }

    /**
     * @param string $table
     * @param string $keyColumn
     * @param array $data_Map
     * @return array
     */
    protected function saveDataMap(string $table, string $keyColumn, array $data_Map)
    {
        $existingKey_List = $this->getKeyList($table, $keyColumn);
        $result = [];
        foreach ($data_Map as $item) {
            $sql = new Sql($this->adapter);
            if (in_array($item[$keyColumn], $existingKey_List)) {
                $update = $sql->update($table);
                $update->where([$keyColumn => $item[$keyColumn]]);
                unset($item[$keyColumn]);
                $update->set($item);
                $result[] = $this->query($update);
            } else {
                $insert = $sql->insert($table);
                $insert->columns(array_keys($item));
                $insert->values(array_values($item));
                $result[] = $this->query($insert);
            }
        }
        return $result;
    }

    /**
     * @param string $table
     * @return AlterTable|CreateTable
     */
    protected function getTableStatement(string $table)
    {
        if (!in_array($table, $this->existingTableList)) {
            $personTable = new CreateTable($table);
        } else {
            $personTable = new AlterTable($table);
        }
        return $personTable;
    }

    /**
     * @param AbstractSql $table
     * @param Column $column
     * @return Column
     * @throws \Exception
     */
    protected function addColumnToTable(AbstractSql $table, Column $column)
    {
        if ($table instanceof CreateTable) {
            $table->addColumn($column);
        }
        if ($table instanceof AlterTable) {
            $columns = $this->metadata->getColumnNames((string)$table->getRawState(AlterTable::TABLE), $this->adapter->getCurrentSchema());
            if (!in_array($column->getName(), $columns)) {
                $table->addColumn($column);
            } else {
                $table->changeColumn($column->getName(), $column);
            }
        }
        return $column;
    }

}
