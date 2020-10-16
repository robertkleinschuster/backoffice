<?php


namespace Base\Authorization\RolePermission;


class RolePermissionBean extends \NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean
{
    public function __construct()
    {
        $this->setDataType('UserRole_ID', self::DATA_TYPE_INT);
        $this->setDataType('UserPermission_Code', self::DATA_TYPE_STRING);
    }

}
