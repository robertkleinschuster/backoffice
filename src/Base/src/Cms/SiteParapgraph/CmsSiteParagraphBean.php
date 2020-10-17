<?php


namespace Base\Cms\SiteParagraph;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsSiteParagraphBean extends AbstractJsonSerializableBean
{

    /**
     * CmsSiteParagraphBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsSite_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('CmsParagraph_ID', self::DATA_TYPE_INT, true);
    }
}
