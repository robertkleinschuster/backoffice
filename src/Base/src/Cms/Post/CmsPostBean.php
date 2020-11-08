<?php

namespace Pars\Base\Cms\Post;

use Pars\Base\Article\Translation\ArticleTranslationBean;

class CmsPostBean extends ArticleTranslationBean
{

    /**
     * CmsPostBean constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDataType('CmsPost_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('CmsPostType_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('CmsPostState_Code', self::DATA_TYPE_STRING, true);
    }
}
