<?php

namespace Pars\Base\Article\Translation;

use Pars\Base\Article\ArticleBean;

/**
 * Class ArticleTranslationBean
 * @package Pars\Base\Article\Translation
 */
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
        $this->setDataType('File_ID', self::DATA_TYPE_INT, true);
    }
}
