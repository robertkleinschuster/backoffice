<?php


namespace Base\File;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class FileBean extends AbstractJsonSerializableBean

{

    /**
     * FileBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('File_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('File_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('File_Name', self::DATA_TYPE_STRING, true);
        $this->setDataType('FileType_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('FileType_Mime', self::DATA_TYPE_STRING, true);
        $this->setDataType('FileType_Name', self::DATA_TYPE_STRING, true);
        $this->setDataType('FileDirectory_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('FileDirectory_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('FileDirectory_Name', self::DATA_TYPE_STRING, true);
    }

}
