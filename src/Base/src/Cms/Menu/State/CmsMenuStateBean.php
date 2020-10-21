<?php
namespace Base\Cms\Menu\State;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsMenuStateBean extends AbstractJsonSerializableBean
{

    /**
     * CmsMenuStateBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsMenuState_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsMenuState_Active', self::DATA_TYPE_BOOL);
    }
}
