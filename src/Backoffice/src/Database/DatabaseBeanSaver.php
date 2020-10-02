<?php


namespace Backoffice\Database;


use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Sql;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanSaver;
use NiceshopsDev\Bean\Database\DatabaseBeanInterface;

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
        if (null == $this->primaryKey_List) {
            throw new \Exception('Primary key list not initialized.');
        }
        return $this->primaryKey_List;
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
        $keys = [];
        foreach ($this->getPrimaryKeyList() as $item) {
            $keys[] = $bean->hasData($item);
        }
        $hasPrimaryKey = !in_array(false, $keys) && count($keys) > 0;
        if ($hasPrimaryKey) {
            return $this->update($bean);
        } else {
            return $this->insert($bean);
        }
    }


    /**
     * @param BeanInterface $bean
     * @return bool
     */
    protected function deleteBean(BeanInterface $bean): bool
    {
        if ($bean instanceof DatabaseBeanInterface) {
            if ($bean->hasPrimaryKeyValue()) {
                $result_List = [];
                $tableList = $this->getTableList();
                $tableList = array_reverse($tableList);
                foreach ($tableList as $table) {
                    $primaryKeys = $bean->getDatabaseFieldName_Map($bean::COLUMN_TYPE_PRIMARY_KEY);
                    if (count($primaryKeys)) {
                        $delete = new Delete($table);
                        foreach ($primaryKeys as $dataName => $dbColumn) {
                            $delete->where("$table.$dbColumn = {$bean->getData($dataName)}");
                        }
                        $result = $this->adapter->query($delete->getSqlString($this->adapter->getPlatform()))->execute();
                        $result_List[] = $result->getAffectedRows() > 0;
                    }
                }
                return !in_array(false, $result_List, true) && count($result_List) > 0;
            }
        }
        return false;
    }

    /**
     * @param DatabaseBeanInterface $bean
     * @return bool
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
                $sql    = new Sql($this->adapter);
                $insert = $sql->insert($table);
                $insert->columns(array_keys($insertdata));
                $insert->values(array_values($insertdata));
                $result = $this->adapter->query($sql->buildSqlString($insert), $this->adapter::QUERY_MODE_EXECUTE);
                if (!$bean->hasPrimaryKeyValue()) {
                    $bean->setPrimaryKeyValue($result->getGeneratedValue());
                }
                $result_List[] = $result->getAffectedRows() > 0;
            }
        }
        return !in_array(false, $result_List, true) && count($result_List) > 0;
    }

    /**
     * @param DatabaseBeanInterface $bean
     * @return bool
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
            if (count($insertdata)) {
                $sql    = new Sql($this->adapter);
                $update = $sql->update($table);
                foreach ($this->getPrimaryKeyList() as $dbColumn) {
                    $dataName = array_flip($this->getFieldColumnMap())[$dbColumn];
                    $update->where("$table.$dbColumn = {$bean->getData($dataName)}");
                }
                $update->set($insertdata);
                $result = $this->adapter->query($sql->buildSqlString($update), $this->adapter::QUERY_MODE_EXECUTE);
                $result_List[] = $result->getAffectedRows() > 0;
            }
        }
        return !in_array(false, $result_List, true) && count($result_List) > 0;
    }


}
