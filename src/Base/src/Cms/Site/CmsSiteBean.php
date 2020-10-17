<?php


namespace Base\Cms\Site;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class CmsSiteBean extends AbstractJsonSerializableBean
{


    /**
     * CmsSiteBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsSite_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('Article_ID', self::DATA_TYPE_INT, true);
    }
}
