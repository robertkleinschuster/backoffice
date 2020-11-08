<?php

namespace Pars\Base\Cms\Menu\State;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class CmsMenuStateBean
 * @package Pars\Base\Cms\Menu\State
 */
class CmsMenuStateBean extends AbstractBaseBean
{

    /**
     * CmsMenuStateBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsMenuState_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsMenuState_Active', self::DATA_TYPE_BOOL);
    }
}
