<?php

namespace Pars\Base\Cms\Paragraph\Type;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

class CmsParagraphTypeBean extends AbstractBaseBean
{

    /**
     * CmsParagraphTypeBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('CmsParagraphType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('CmsParagraphType_Active', self::DATA_TYPE_BOOL);
    }
}
