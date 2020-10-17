<?php


namespace Base\Article;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class ArticleBean extends AbstractJsonSerializableBean
{
    public function __construct()
    {
        $this->setDataType('Article_ID', self::DATA_TYPE_INT);
        $this->setDataType('Article_Code', self::DATA_TYPE_STRING);
        $this->setDataType('ArticleState_Code', self::DATA_TYPE_STRING);
        $this->setDataType('ArticleType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('ArticleTranslation_BeanList', self::DATA_TYPE_ITERABLE);
    }

}
