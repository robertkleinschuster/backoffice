<?php


namespace Base\Article\Type;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class ArticleTypeBean extends AbstractJsonSerializableBean
{

    /**
     * ArticleTypeBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('ArticleType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('ArticleType_Active', self::DATA_TYPE_BOOL);
    }
}
