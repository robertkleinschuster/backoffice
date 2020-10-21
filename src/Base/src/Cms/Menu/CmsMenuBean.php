<?php


namespace Base\Cms\Menu;


use Base\Article\Translation\ArticleTranslationBean;

class CmsMenuBean extends ArticleTranslationBean
{

    /**
     * CmsMenuBean constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDataType('CmsMenu_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('CmsMenu_ID_Parent', self::DATA_TYPE_INT, true);
        $this->setDataType('CmsSite_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('CmsSite_ID_Parent', self::DATA_TYPE_INT, true);
        $this->setDataType('CmsMenu_Order', self::DATA_TYPE_INT, true);
        $this->setDataType('CmsMenuType_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('CmsMenuState_Code', self::DATA_TYPE_STRING, true);
    }

}
