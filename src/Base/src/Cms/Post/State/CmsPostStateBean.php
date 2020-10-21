<?php
namespace Base\Cms\Post\State;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsPostStateBean extends AbstractJsonSerializableBean
{

    /**
     * CmsPostStateBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsPostState_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsPostState_Active', self::DATA_TYPE_BOOL);
    }
}
