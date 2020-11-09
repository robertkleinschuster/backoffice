<?php

namespace Pars\Base\Cms\Page\Type;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class CmsPageTypeBean
 * @package Pars\Base\Cms\Page\Type
 */
class CmsPageTypeBean extends AbstractBaseBean
{

    /**
     * CmsPageTypeBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsPageType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsPageType_Active', self::DATA_TYPE_BOOL);
    }
}
