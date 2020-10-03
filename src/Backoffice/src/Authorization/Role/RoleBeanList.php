<?php
namespace Backoffice\Authorization\Role;

use NiceshopsDev\Bean\BeanList\JsonSerializable\AbstractJsonSerializableBeanList;

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
