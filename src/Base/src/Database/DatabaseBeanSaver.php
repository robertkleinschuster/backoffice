<?php


namespace Base\Database;


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
     * @var int
     */
    private $personId;

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
     * @return int
     */
    public function getPersonId(): int
    {
        return $this->personId;
    }

    /**
     * @param int $personId
     *
     * @return $this
     */
    public function setPersonId(int $personId): self
    {
        $this->personId = $personId;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasPersonId(): bool
    {
        return $this->personId !== null;
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

    /**
     * @param BeanInterface $bean
     * @return bool
     * @throws \Exception
     */
    protected function hasPrimaryKeyValue(BeanInterface $bean): bool
    {
        $keys = [];
        foreach ($this->getPrimaryKeyList() as $item) {
            $keys[] = $bean->hasData($this->getFieldNameByColumn($item));
        }

        $hasPrimaryKey = !in_array(false, $keys) && count($keys) > 0;
        return $hasPrimaryKey;
    }

    /**
     * @param string $column
     * @return string
     * @throws \Exception
     */
    protected function getFieldNameByColumn(string $column, string $table = null): string
    {
        $columns = array_flip($this->getFieldColumnMap());
        if ($table === null) {
            foreach ($this->getTableList() as $table) {
                if (!str_contains($column, '.')) {
                    $column = "$table.$column";
                }
            }
        } else {
            $column = "$table.$column";
        }
        if (isset($columns[$column])) {
            return $columns[$column];
        }
        throw new \Exception("No bean field found for column $column.");
    }

    /**
     * @param string $dbColumn
     * @param string|null $table
     * @return bool
     */
    public function validateColumnName(string $dbColumn, string $table = null)
    {
        $exp = explode('.', $dbColumn);
        if (is_array($exp) && count($exp) === 2 && !empty(trim($exp[0])) && !empty(trim($exp[1]))) {
            if ($table === null) {
                return true;
            } elseif ($exp[0] == $table) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     * @throws \Exception
     */
    protected function deleteBean(BeanInterface $bean): bool
    {
        $formatter = new DatabaseBeanFormatter();
        $result_List = [];
        $tableList = $this->getTableList();
        $tableList = array_reverse($tableList);
        foreach ($tableList as $table) {
            $deletedata = [];
            foreach ($this->getFieldColumnMap() as $dataName => $dbColumn) {
                if ($bean->hasData($dataName) && $this->validateColumnName($dbColumn, $table)) {
                    $columnName = explode('.', $dbColumn)[0];
                    if (in_array($columnName, $this->getPrimaryKeyList())) {
                        $dbColumn = "$table.$columnName";
                    }
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
        $result_List = [];
        foreach ($this->getTableList() as $table) {
            $insertdata = [];

            foreach ($this->getFieldColumnMap() as $dataName => $dbColumn) {
                if ($bean->hasData($dataName) && $this->validateColumnName($dbColumn, $table)) {
                    $insertdata[str_replace("$table.", '', $dbColumn)] = $formatter->format($bean)->getValue($dataName);
                }
            }
            foreach ($this->getPrimaryKeyList() as $pkColumn) {
                if ($bean->hasData($this->getFieldNameByColumn($pkColumn))) {
                    $insertdata[$pkColumn] = $bean->getData($this->getFieldNameByColumn($pkColumn));
                }
            }

            if (count($insertdata)) {
                $dateTime = new \DateTime();
                $insertdata['Timestamp_Create'] = $dateTime->format('Y-m-d H:i:s');
                $insertdata['Timestamp_Edit'] = $dateTime->format('Y-m-d H:i:s');
                if ($this->hasPersonId()) {
                    $insertdata['Person_ID_Create'] = $this->getPersonId();
                    $insertdata['Person_ID_Edit'] = $this->getPersonId();
                }

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
        $result_List = [];
        foreach ($this->getTableList() as $table) {
            $insertdata = [];
            foreach ($this->getFieldColumnMap() as $dataName => $dbColumn) {
                if ($bean->hasData($dataName) && $this->validateColumnName($dbColumn, $table)) {
                    $insertdata[$dbColumn] = $formatter->format($bean)->getValue($dataName);
                }
            }
            if (count($insertdata) && count($this->getPrimaryKeyList())) {
                $dateTime = new \DateTime();
                $insertdata['Timestamp_Edit'] = $dateTime->format('Y-m-d H:i:s');
                if ($this->hasPersonId()) {
                    $insertdata['Person_ID_Edit'] = $this->getPersonId();
                }
                $sql = new Sql($this->adapter);
                $update = $sql->update($table);
                foreach ($this->getPrimaryKeyList() as $dbColumn) {
                    $update->where(["$table.$dbColumn" => $bean->getData($this->getFieldNameByColumn($dbColumn))]);
                }
                $update->set($insertdata);
                $result = $this->adapter->query($sql->buildSqlString($update), $this->adapter::QUERY_MODE_EXECUTE);
                $result_List[] = $result->getAffectedRows() > 0;
            }
        }
        return !in_array(false, $result_List, true) && count($result_List) > 0;
    }

}
