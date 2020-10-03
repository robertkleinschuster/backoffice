<?php

namespace Backoffice\Mvc\Base;


use NiceshopsDev\Bean\AbstractBaseBean;

class BackofficeBeanFormatter extends \NiceshopsDev\Bean\BeanFormatter\AbstractBeanFormatter
{
    /**
     * @param string|null $dataType
     * @param $value
     * @return false|mixed|string
     * @throws \Exception
     */
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
        throw new \Exception("Unabled to convert $dataType to backoffice.");
    }

    protected function formatValueByName(string $name, $value, $originalValue)
    {
        return $value;
    }

}
