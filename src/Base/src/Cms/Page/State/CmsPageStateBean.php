<?php

namespace Pars\Base\Cms\Page\State;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class CmsPageStateBean
 * @package Pars\Base\Cms\Page\State
 */
class CmsPageStateBean extends AbstractBaseBean
{

    /**
     * CmsPageStateBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsPageState_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsPageState_Active', self::DATA_TYPE_BOOL);
    }
}
