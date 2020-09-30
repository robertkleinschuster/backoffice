<?php


namespace Backoffice\Database;


use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;
use NiceshopsDev\Bean\AbstractBaseBean;
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
     * @param BeanInterface $bean
     * @return bool
     */
    protected function saveBean(BeanInterface $bean): bool
    {
        if ($bean instanceof DatabaseBeanInterface) {
            if ($bean->hasPrimaryKeyValue()) {
                return $this->update($bean);
            } else {
                return $this->insert($bean);
            }
        }
        return false;
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
     * @param $value
     * @param string $type
     * @return false|string
     * @throws \Exception
     */
    protected function convertValueToDatabase($value, string $type)
    {
        if ($value === null) {
            return "NULL";
        }
        switch ($type) {
            case AbstractBaseBean::DATA_TYPE_FLOAT:
            case AbstractBaseBean::DATA_TYPE_INT:
            case AbstractBaseBean::DATA_TYPE_STRING:
                return strval($value);
            case AbstractBaseBean::DATA_TYPE_BOOL:
                if ($value) {
                    return 'true';
                } else {
                    return 'false';
                }
            case AbstractBaseBean::DATA_TYPE_ARRAY:
                return json_encode($value);
            case AbstractBaseBean::DATA_TYPE_DATE:
            case AbstractBaseBean::DATA_TYPE_DATETIME_PHP:
                if ($value instanceof \DateTime) {
                    return $value->format('Y-m-d H:i:s');
                }
        }
        throw new \Exception("Unabled to convert $type to db.");
    }

    /**
     * @param DatabaseBeanInterface $bean
     * @return bool
     */
    protected function insert(DatabaseBeanInterface $bean): bool
    {
        $metadata = \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($this->adapter);
        $result_List = [];
        foreach ($this->getTableList() as $table) {
            $tableColumn_List = $metadata->getColumnNames($table, $this->adapter->getCurrentSchema());
            $insertdata = [];
            foreach ($bean->getDatabaseFieldName_Map() as $dataName => $dbColumn) {
                if ($bean->hasData($dataName) && in_array($dbColumn, $tableColumn_List)) {
                    $insertdata[$dbColumn] = $this->convertValueToDatabase($bean->getData($dataName), $bean->getDataType($dataName));
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
    protected function update(DatabaseBeanInterface $bean): bool
    {
        $metadata = \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($this->adapter);
        $result_List = [];
        foreach ($this->getTableList() as $table) {
            $tableColumn_List = $metadata->getColumnNames($table, $this->adapter->getCurrentSchema());
            $insertdata = [];
            foreach ($bean->getDatabaseFieldName_Map() as $dataName => $dbColumn) {
                if ($bean->hasData($dataName) && in_array($dbColumn, $tableColumn_List)) {
                    $insertdata[$dbColumn] = $this->convertValueToDatabase($bean->getData($dataName), $bean->getDataType($dataName));
                }
            }
            if (count($insertdata)) {
                $sql    = new Sql($this->adapter);
                $update = $sql->update($table);
                foreach ($bean->getDatabaseFieldName_Map($bean::COLUMN_TYPE_PRIMARY_KEY) as $dataName => $dbColumn) {
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
