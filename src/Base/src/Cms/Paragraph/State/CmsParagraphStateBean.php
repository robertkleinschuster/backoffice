<?php
namespace Base\Cms\Paragraph\State;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsParagraphStateBean extends AbstractJsonSerializableBean
{

    /**
     * CmsParagraphStateBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsParagraphState_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsParagraphState_Active', self::DATA_TYPE_BOOL);
    }
}
