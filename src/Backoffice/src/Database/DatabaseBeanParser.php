<?php


namespace Backoffice\Database;


use NiceshopsDev\Bean\AbstractBaseBean;
use NiceshopsDev\Bean\BeanParser\AbstractBeanParser;

class DatabaseBeanParser extends AbstractBeanParser
{
    protected function convertValueByDataType(?string $dataType, $value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        switch ($dataType) {
            case AbstractBaseBean::DATA_TYPE_STRING:
                return strval($value);
            case AbstractBaseBean::DATA_TYPE_BOOL:
                if ($value === 'true') {
                    return true;
                } elseif ($value === 'false') {
                    return false;
                }
            case AbstractBaseBean::DATA_TYPE_INT:
                return intval($value);
            case AbstractBaseBean::DATA_TYPE_FLOAT:
                return boolval($value);
            case AbstractBaseBean::DATA_TYPE_ARRAY:
                return json_decode($value);
            case AbstractBaseBean::DATA_TYPE_DATE:
            case AbstractBaseBean::DATA_TYPE_DATETIME_PHP:
                return \DateTime::createFromFormat('Y-m-d H:i:s', $value);

        }
        throw new \Exception("Unabled to convert $dataType from db.");
    }

    protected function parseValueByName(string $name, $value, $originalValue)
    {
        return $value;
    }


}
