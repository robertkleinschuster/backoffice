<?php


namespace Base\Cms\Menu\Type;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsMenuTypeBean extends AbstractJsonSerializableBean
{

    /**
     * CmsMenuTypeBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsMenuType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsMenuType_Active', self::DATA_TYPE_BOOL);
    }
}
