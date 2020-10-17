<?php


namespace Base\Cms\Paragraph;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsParagraphBean extends AbstractJsonSerializableBean
{

    /**
     * CmsParagraphBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsParagraph_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('Article_ID', self::DATA_TYPE_INT, true);
    }
}
