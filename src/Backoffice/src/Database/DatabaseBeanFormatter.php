<?php


namespace Backoffice\Database;


use NiceshopsDev\Bean\AbstractBaseBean;
use NiceshopsDev\Bean\BeanFormatter\AbstractBeanFormatter;

class DatabaseBeanFormatter extends AbstractBeanFormatter
{

    protected function convertValueByDataType(?string $dataType, $value)
    {
        if ($value === null) {
            return "NULL";
        }
        switch ($dataType) {
            case AbstractBaseBean::DATA_TYPE_FLOAT:
            case AbstractBaseBean::DATA_TYPE_INT:
            case AbstractBaseBean::DATA_TYPE_STRING:
                return strval($value);
            case AbstractBaseBean::DATA_TYPE_BOOL:
                if ($value) {
                    return 1;
                } else {
                    return 0;
                }
            case AbstractBaseBean::DATA_TYPE_ARRAY:
                return json_encode($value);
            case AbstractBaseBean::DATA_TYPE_DATE:
            case AbstractBaseBean::DATA_TYPE_DATETIME_PHP:
                if ($value instanceof \DateTime) {
                    return $value->format('Y-m-d H:i:s');
                }
        }
        throw new \Exception("Unabled to convert $dataType to db.");
    }

    protected function formatValueByName(string $name, $value, $originalValue)
    {
        return $value;
    }
}
