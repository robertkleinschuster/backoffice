<?php
namespace Base\Cms\Site\State;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsSiteStateBean extends AbstractJsonSerializableBean
{

    /**
     * CmsSiteStateBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsSiteState_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsSiteState_Active', self::DATA_TYPE_BOOL);
    }
}
