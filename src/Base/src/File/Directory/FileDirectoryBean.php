<?php
namespace Base\File\Directory;



use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class FileDirectoryBean extends AbstractJsonSerializableBean
{

    /**
     * FileDirectoryBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('FileDirectory_Code', self::DATA_TYPE_STRING);
        $this->setDataType('FileDirectory_Name', self::DATA_TYPE_STRING);
        $this->setDataType('FileDirectory_Active', self::DATA_TYPE_BOOL);
    }
}
