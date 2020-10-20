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
        $this->setDataType('CmsSite_CmsParagraph_Order', self::DATA_TYPE_INT, true);
        $this->setDataType('Article_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('Article_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('Locale_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Name', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Title', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Heading', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_SubHeading', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Teaser', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Text', self::DATA_TYPE_STRING, true);
        $this->setDataType('ArticleTranslation_Footer', self::DATA_TYPE_STRING, true);
    }
}
