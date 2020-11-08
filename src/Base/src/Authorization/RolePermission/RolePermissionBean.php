<?php

namespace Pars\Base\Authorization\RolePermission;

use Niceshops\Bean\Type\JsonSerializable\AbstractJsonSerializableBean;

/**
 * Class RolePermissionBean
 * @package Pars\Base\Authorization\RolePermission
 */
class RolePermissionBean extends AbstractJsonSerializableBean
{
    public function __construct()
    {
        $this->setDataType('UserRole_ID', self::DATA_TYPE_INT);
        $this->setDataType('UserPermission_Code', self::DATA_TYPE_STRING);
        $this->setDataType('UserPermission_Active', self::DATA_TYPE_BOOL);
    }
}
