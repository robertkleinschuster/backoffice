<?php

namespace Pars\Base\Authorization\Permission;

use Niceshops\Bean\Type\JsonSerializable\AbstractJsonSerializableBean;

class PermissionBean extends AbstractJsonSerializableBean
{
    public function __construct()
    {
        $this->setDataType('UserPermission_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('UserPermission_Active', self::DATA_TYPE_BOOL, true);
    }
}
