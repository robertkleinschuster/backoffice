<?php


namespace Base\Cms\Site\Type;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsSiteTypeBean extends AbstractJsonSerializableBean
{

    /**
     * CmsSiteTypeBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsSiteType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsSiteType_Active', self::DATA_TYPE_BOOL);
    }
}
