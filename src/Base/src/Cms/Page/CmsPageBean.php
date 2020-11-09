<?php

namespace Pars\Base\Cms\Page;

use Pars\Base\Article\Translation\ArticleTranslationBean;

/**
 * Class CmsPageBean
 * @package Pars\Base\Cms\Page
 */
class CmsPageBean extends ArticleTranslationBean
{

    /**
     * CmsPageBean constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDataType('CmsPage_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('CmsPageType_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('CmsPageType_Template', self::DATA_TYPE_STRING, true);
        $this->setDataType('CmsPageState_Code', self::DATA_TYPE_STRING, true);
    }
}
