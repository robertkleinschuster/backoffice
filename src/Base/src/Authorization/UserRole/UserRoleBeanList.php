<?php


namespace Base\Authorization\UserRole;


class UserRoleBeanList extends \NiceshopsDev\Bean\BeanList\JsonSerializable\AbstractJsonSerializableBeanList
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
