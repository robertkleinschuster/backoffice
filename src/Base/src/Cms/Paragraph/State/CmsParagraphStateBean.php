<?php

namespace Pars\Base\Cms\Paragraph\State;


use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class CmsParagraphStateBean
 * @package Pars\Base\Cms\Paragraph\State
 */
class CmsParagraphStateBean extends AbstractBaseBean
{

    /**
     * CmsParagraphStateBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsParagraphState_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsParagraphState_Active', self::DATA_TYPE_BOOL);
    }
}
