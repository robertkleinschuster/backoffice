<?php


namespace Base\Article;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class ArticleBean extends AbstractJsonSerializableBean
{

    public const STATE_ACTIVE = 'active';
    public const STATE_INACTIVE = 'inactive';

    public const TYPE_DEFAULT = 'default';

    public function __construct()
    {
        $this->setDataType('Article_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('Article_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleState_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleType_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_BeanList', self::DATA_TYPE_ITERABLE, true);
    }

}
