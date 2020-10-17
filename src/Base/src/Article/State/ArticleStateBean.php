<?php
namespace Base\Article\State;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class ArticleStateBean extends AbstractJsonSerializableBean
{

    /**
     * ArticleStateBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('ArticleState_Code', self::DATA_TYPE_STRING);
        $this->setDataType('ArticleState_Active', self::DATA_TYPE_BOOL);
    }
}
