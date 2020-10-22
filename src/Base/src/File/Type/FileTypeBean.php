<?php


namespace Base\File\Type;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class FileTypeBean extends AbstractJsonSerializableBean
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
