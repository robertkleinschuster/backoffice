<?php

namespace Pars\Base\Cms\Site\State;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class CmsSiteStateBean
 * @package Pars\Base\Cms\Site\State
 */
class CmsSiteStateBean extends AbstractBaseBean
{

    /**
     * CmsSiteStateBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsSiteState_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsSiteState_Active', self::DATA_TYPE_BOOL);
    }
}
