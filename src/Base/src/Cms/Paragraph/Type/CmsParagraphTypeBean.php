<?php


namespace Base\Cms\Paragraph\Type;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsParagraphTypeBean extends AbstractJsonSerializableBean
{

    /**
     * CmsParagraphTypeBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsParagraphType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsParagraphType_Active', self::DATA_TYPE_BOOL);
    }
}
