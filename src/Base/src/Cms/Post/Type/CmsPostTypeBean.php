<?php

namespace Pars\Base\Cms\Post\Type;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class CmsPostTypeBean
 * @package Pars\Base\Cms\Post\Type
 */
class CmsPostTypeBean extends AbstractBaseBean
{

    /**
     * CmsPostTypeBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsPostType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsPostType_Active', self::DATA_TYPE_BOOL);
    }
}
