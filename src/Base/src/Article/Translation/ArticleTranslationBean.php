<?php


namespace Base\Article\Translation;


use Base\Article\ArticleBean;

class ArticleTranslationBean extends ArticleBean
{

    /**
     * ArticleTranslationBean constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDataType('Locale_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Name', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Title', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Heading', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_SubHeading', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Teaser', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Text', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Footer', self::DATA_TYPE_STRING, true);
    }
}
