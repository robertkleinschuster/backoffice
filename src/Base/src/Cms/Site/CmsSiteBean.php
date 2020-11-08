<?php

namespace Pars\Base\Cms\Site;

use Pars\Base\Article\Translation\ArticleTranslationBean;

/**
 * Class CmsSiteBean
 * @package Pars\Base\Cms\Site
 */
class CmsSiteBean extends ArticleTranslationBean
{

    /**
     * CmsSiteBean constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDataType('CmsSite_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('CmsSiteType_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('CmsSiteType_Template', self::DATA_TYPE_STRING, true);
        $this->setDataType('CmsSiteState_Code', self::DATA_TYPE_STRING, true);
    }
}
