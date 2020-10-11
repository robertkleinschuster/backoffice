<?php


namespace Base\Database;


use NiceshopsDev\Bean\BeanInterface;

trait DatabaseInfoTrait
{

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
     * @return $this
     */
    public function setPrimaryKeyList(array $primaryKey_List)
    {
        $this->primaryKey_List = $primaryKey_List;
        return $this;
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
}
