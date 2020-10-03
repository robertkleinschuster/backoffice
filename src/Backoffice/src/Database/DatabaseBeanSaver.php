<?php


namespace Backoffice\Database;


use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Sql;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanSaver;

class DatabaseBeanSaver extends AbstractBeanSaver implements AdapterAwareInterface
{

    use AdapterAwareTrait;

    /**
     * @var string[]
     */
    protected $table_List;

    /**
     * @var string[]
     */
    private $fieldColumn_Map;

    /**
     * @var string[]
     */
    private $primaryKey_List;

    /**
     * DatabaseBeanSaver constructor.
     * @param Adapter $adapter
     * @param string[] $table
     */
    public function __construct(Adapter $adapter, ...$table)
    {
        $this->setDbAdapter($adapter);
        $this->setTableList($table);
    }

    /**
     * @return string[]
     */
    protected function getTableList(): array
    {
        return $this->table_List;
    }

    /**
     * @param string[] $table_List
     */
    protected function setTableList(array $table_List): void
    {
        $this->table_List = $table_List;
    }

    /**
     * @return string[]
     * @throws \Exception
     */
    public function getFieldColumnMap(): array
    {
        if (null == $this->fieldColumn_Map) {
            throw new \Exception('Field column map not initialized.');
        }
        return $this->fieldColumn_Map;
    }

    /**
     * @param string[] $fieldColumn_Map
     * @return $this
     */
    public function setFieldColumnMap(array $fieldColumn_Map)
    {
        $this->fieldColumn_Map = $fieldColumn_Map;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getPrimaryKeyList(): array
    {
        return $this->primaryKey_List ?? [];
    }

    /**
     * @param string[] $primaryKey_List
     * @return DatabaseBeanSaver
     */
    public function setPrimaryKeyList(array $primaryKey_List)
    {
        $this->primaryKey_List = $primaryKey_List;
        return $this;
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     */
    protected function saveBean(BeanInterface $bean): bool
    {
        if ($this->hasPrimaryKeyValue($bean)) {
            return $this->update($bean);
        } else {
            return $this->insert($bean);
        }
    }


    protected function hasPrimaryKeyValue(BeanInterface $bean): bool
    {
        $keys = [];
        foreach ($this->getPrimaryKeyList() as $item) {
            $keys[] = $bean->hasData($this->getFieldNameByColumn($item));
        }
        $hasPrimaryKey = !in_array(false, $keys) && count($keys) > 0;
        return $hasPrimaryKey;
    }

    protected function getFieldNameByColumn(string $column): string
    {
        $name = array_flip($this->getFieldColumnMap())[$column];
        if (null == $name) {
            throw new \Exception("No bean field found for column $column.");
        }
        return $name;
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     * @throws \Exception
     */
    protected function deleteBean(BeanInterface $bean): bool
    {
        $formatter = new DatabaseBeanFormatter();
        $metadata = \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($this->adapter);

        $result_List = [];
        $tableList = $this->getTableList();
        $tableList = array_reverse($tableList);
        foreach ($tableList as $table) {
            $tableColumn_List = $metadata->getColumnNames($table, $this->adapter->getCurrentSchema());
            $deletedata = [];
            foreach ($this->getFieldColumnMap() as $dataName => $dbColumn) {
                if ($bean->hasData($dataName) && in_array($dbColumn, $tableColumn_List)) {
                    $deletedata[$dbColumn] = $formatter->format($bean)->getValue($dataName);
                }
            }
            if (count($deletedata)) {
                $delete = new Delete($table);
                $delete->where($deletedata);
                $result = $this->adapter->query($delete->getSqlString($this->adapter->getPlatform()))->execute();
                $result_List[] = $result->getAffectedRows() > 0;
            }
        }
        return !in_array(false, $result_List, true) && count($result_List) > 0;

    }

    /**
     * @param BeanInterface $bean
     * @return bool
     * @throws \Exception
     */
    protected function insert(BeanInterface $bean): bool
    {
        $formatter = new DatabaseBeanFormatter();
        $metadata = \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($this->adapter);
        $result_List = [];
        foreach ($this->getTableList() as $table) {
            $tableColumn_List = $metadata->getColumnNames($table, $this->adapter->getCurrentSchema());
            $insertdata = [];

            foreach ($this->getFieldColumnMap() as $dataName => $dbColumn) {
                if ($bean->hasData($dataName) && in_array($dbColumn, $tableColumn_List)) {
                    $insertdata[$dbColumn] = $formatter->format($bean)->getValue($dataName);
                }
            }
            if (count($insertdata)) {
                $sql = new Sql($this->adapter);
                $insert = $sql->insert($table);
                $insert->columns(array_keys($insertdata));
                $insert->values(array_values($insertdata));
                $result = $this->adapter->query($sql->buildSqlString($insert), $this->adapter::QUERY_MODE_EXECUTE);
                if (count($this->getPrimaryKeyList()) == 1) {
                    foreach ($this->getPrimaryKeyList() as $item) {
                        $bean->setData($this->getFieldNameByColumn($item), $result->getGeneratedValue());
                    }
                }
                $result_List[] = $result->getAffectedRows() > 0;
            }
        }
        return !in_array(false, $result_List, true) && count($result_List) > 0;
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     * @throws \Exception
     */
    protected function update(BeanInterface $bean): bool
    {
        $formatter = new DatabaseBeanFormatter();
        $metadata = \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($this->adapter);
        $result_List = [];
        foreach ($this->getTableList() as $table) {
            $tableColumn_List = $metadata->getColumnNames($table, $this->adapter->getCurrentSchema());
            $insertdata = [];
            foreach ($this->getFieldColumnMap() as $dataName => $dbColumn) {
                if ($bean->hasData($dataName) && in_array($dbColumn, $tableColumn_List)) {
                    $insertdata[$dbColumn] = $formatter->format($bean)->getValue($dataName);
                }
            }
            if (count($insertdata) && count($this->getPrimaryKeyList())) {
                $sql = new Sql($this->adapter);
                $update = $sql->update($table);
                foreach ($this->getPrimaryKeyList() as $dbColumn) {
                    $update->where(["$table.$dbColumn" => $insertdata[$dbColumn]]);
                }
                $update->set($insertdata);
                $result = $this->adapter->query($sql->buildSqlString($update), $this->adapter::QUERY_MODE_EXECUTE);
                $result_List[] = $result->getAffectedRows() > 0;
            }
        }
        return !in_array(false, $result_List, true) && count($result_List) > 0;
    }


}
