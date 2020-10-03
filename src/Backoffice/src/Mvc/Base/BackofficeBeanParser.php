<?php
namespace Backoffice\Mvc\Base;

use NiceshopsDev\Bean\AbstractBaseBean;

class BackofficeBeanParser extends \NiceshopsDev\Bean\BeanParser\AbstractBeanParser
{
    /***
     * @param string|null $dataType
     * @param $value
     * @return bool|\DateTime|false|int|mixed|string|null
     * @throws \Exception
     */
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
        return $value;
    }

    protected function parseValueByName(string $name, $value, $originalValue)
    {
        return $value;
    }

}
