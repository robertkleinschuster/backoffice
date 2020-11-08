<?php

namespace Pars\Base\Cms\Menu\Type;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class CmsMenuTypeBean
 * @package Pars\Base\Cms\Menu\Type
 */
class CmsMenuTypeBean extends AbstractBaseBean
{

    /**
     * CmsMenuTypeBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsMenuType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsMenuType_Active', self::DATA_TYPE_BOOL);
    }
}
