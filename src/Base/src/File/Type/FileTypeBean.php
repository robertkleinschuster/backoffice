<?php

namespace Pars\Base\File\Type;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class FileTypeBean
 * @package Pars\Base\File\Type
 */
class FileTypeBean extends AbstractBaseBean
{

    /**
     * FileTypeBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('FileType_Code', self::DATA_TYPE_STRING);
        $this->setDataType('FileType_Name', self::DATA_TYPE_STRING);
        $this->setDataType('FileType_Mime', self::DATA_TYPE_STRING);
        $this->setDataType('FileType_Active', self::DATA_TYPE_BOOL);
    }
}
