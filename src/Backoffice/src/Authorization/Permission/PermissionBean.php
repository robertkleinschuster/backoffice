<?php
namespace Backoffice\Authorization\Permission;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class PermissionBean extends AbstractJsonSerializableBean
{
    public function __construct()
    {
        $this->setDataType('UserPermission_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('UserRole_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('UserPermission_Active', self::DATA_TYPE_BOOL, true);
    }


}
