<?php

namespace Pars\Base\Authorization\Role;

use Niceshops\Bean\Type\JsonSerializable\AbstractJsonSerializableBeanList;

/**
 * Class RoleBeanList
 * @package Pars\Base\Authorization\Role
 */
class RoleBeanList extends AbstractJsonSerializableBeanList
{
    public function getPermission_List(): array
    {
        $permissionsLists = $this->getData('userpermission_beanlist');
        $permissionCode_List = [];
        foreach ($permissionsLists as $permissionsList) {
            $permissionCode_List = array_merge($permissionCode_List, $permissionsList->getData('userpermission_code'));
        }
        return $permissionCode_List;
    }
}
