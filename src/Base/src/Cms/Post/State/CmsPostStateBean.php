<?php

namespace Pars\Base\Cms\Post\State;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class CmsPostStateBean
 * @package Pars\Base\Cms\Post\State
 */
class CmsPostStateBean extends AbstractBaseBean
{

    /**
     * CmsPostStateBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsPostState_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsPostState_Active', self::DATA_TYPE_BOOL);
    }
}
