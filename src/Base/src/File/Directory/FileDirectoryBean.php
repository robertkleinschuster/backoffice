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
        $this->setDataType('FileDirectory_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('FileDirectory_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('FileDirectory_Name', self::DATA_TYPE_STRING, true);
        $this->setDataType('FileDirectory_Active', self::DATA_TYPE_BOOL, true);
        $this->setDataType('File_BeanList', self::DATA_TYPE_ITERABLE, true);
    }
}
