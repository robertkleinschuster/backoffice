<?php

namespace Pars\Base\Authorization\UserRole;

use Niceshops\Bean\Type\JsonSerializable\AbstractJsonSerializableBeanList;

/**
 * Class UserRoleBeanList
 * @package Pars\Base\Authorization\UserRole
 */
class UserRoleBeanList extends AbstractJsonSerializableBeanList
{
    public function getPermission_List(): array
    {
        $permissionsLists = $this->getData('UserPermission_BeanList');
        $permissionCode_List = [];
        foreach ($permissionsLists as $permissionsList) {
            $permissionCode_List = array_merge($permissionCode_List, $permissionsList->getData('UserPermission_Code'));
        }
        return $permissionCode_List;
    }
}
