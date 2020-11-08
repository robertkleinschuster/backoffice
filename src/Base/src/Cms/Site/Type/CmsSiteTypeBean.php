<?php

namespace Pars\Base\Cms\Site\Type;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class CmsSiteTypeBean
 * @package Pars\Base\Cms\Site\Type
 */
class CmsSiteTypeBean extends AbstractBaseBean
{

    /**
     * CmsSiteTypeBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsSiteType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsSiteType_Active', self::DATA_TYPE_BOOL);
    }
}
