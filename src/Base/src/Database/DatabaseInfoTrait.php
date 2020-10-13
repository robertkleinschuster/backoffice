<?php


namespace Base\Database;


trait DatabaseInfoTrait
{
    /**
     * @var array
     */
    private array $dbInfo_Map = [];

    /**
     * @param string $field
     * @param string $column
     * @param string $table
     * @param string $joinField
     * @param bool $isKey
     * @param string|null $joinFieldSelf
     * @return $this
     */
    public function addColumn(string $field, string $column, string $table, string $joinField, bool $isKey = false, string $joinFieldSelf = null)
    {
        if (null === $joinFieldSelf) {
            $joinFieldSelf = $joinField;
        }
        $this->dbInfo_Map[$field] = ['column' => $column, 'table' => $table, 'joinField' => $joinField, 'isKey' => $isKey, 'joinFieldSelf' => $joinFieldSelf];
        return $this;
    }

    /**
     * @param string|null $table
     * @return array
     */
    private function getField_List(string $table = null): array
    {
        if (null === $table) {
            return array_keys($this->dbInfo_Map);
        } else {
            return array_keys(array_filter($this->dbInfo_Map, function ($item) use ($table) {return $item['table'] === $table;}));
        }
    }

    /**
     * @param string $field
     * @return bool
     */
    private function hasField(string $field)
    {
        return isset($this->dbInfo_Map[$field]);
    }

    /**
     * @return array
     */
    private function getTable_List(): array
    {
       return array_unique(array_column($this->dbInfo_Map, 'table'));
    }

    /**
     * @param string $field
     * @return array
     * @throws \Exception
     */
    private function getTable(string $field): string
    {
        return $this->getInfo($field, 'table');
    }

    /**
     * @param string $field
     * @return string
     * @throws \Exception
     */
    private function getJoinField(string $field): string
    {
        return $this->getInfo($field, 'joinField');
    }

    /**
     * @param string $field
     * @return string
     * @throws \Exception
     */
    private function getJoinFieldSelf(string $field): string
    {
        return $this->getInfo($field, 'joinFieldSelf');
    }

    /**
     * @param string $field
     * @return mixed
     * @throws \Exception
     */
    private function getColumn(string $field): string
    {
        if (!isset($this->dbInfo_Map[$field])) {
            throw new \Exception('No column found for field ' . $field);
        }
        return $this->dbInfo_Map[$field]['column'];
    }

    /**
     * @param string $field
     * @param string $key
     * @return string
     * @throws \Exception
     */
    private function getInfo(string $field, string $key): string
    {
        if (!isset($this->dbInfo_Map[$field])) {
            throw new \Exception("Field $field not found in db info.");
        }
        return $this->dbInfo_Map[$field][$key];
    }

    /**
     * @return array
     */
    private function getKeyField_List(): array
    {
        return array_keys(array_filter($this->dbInfo_Map, function ($item) {return $item['isKey'];}));
    }


    /**
     * @param string $column
     * @return int|string
     * @throws \Exception
     */
    private function getField(string $column): string
    {
        foreach ($this->dbInfo_Map as $field => $item) {
            if ($item['column'] === $column) {
                return $field;
            }
        }
        throw new \Exception('No field found for column ' . $column);
    }
}
