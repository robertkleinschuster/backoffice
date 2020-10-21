<?php


namespace Base\Cms\Post\Type;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsPostTypeBean extends AbstractJsonSerializableBean
{

    /**
     * CmsPostTypeBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsPostType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsPostType_Active', self::DATA_TYPE_BOOL);
    }
}
