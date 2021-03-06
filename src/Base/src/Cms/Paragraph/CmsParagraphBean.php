<?php


namespace Base\Cms\Paragraph;


use Base\Article\Translation\ArticleTranslationBean;

class CmsParagraphBean extends ArticleTranslationBean
{

    /**
     * CmsParagraphBean constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDataType('CmsParagraph_ID', self::DATA_TYPE_INT, true);
    }
}
