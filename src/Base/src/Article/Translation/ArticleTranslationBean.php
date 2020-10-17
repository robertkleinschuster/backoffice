<?php


namespace Base\Article\Translation;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class ArticleTranslationBean extends AbstractJsonSerializableBean
{

    /**
     * ArticleTranslationBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('Article_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('Locale_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Name', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Title', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Heading', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_SubHeading', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Teaser', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Text', self::DATA_TYPE_STRING, true);
    }
}
