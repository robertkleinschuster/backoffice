<?php


namespace Backoffice\Mvc\UserRole;


use Backoffice\Authorization\Role\RoleBeanFinder;
use Backoffice\Authorization\UserRole\UserRoleBeanFinder;
use Backoffice\Authorization\UserRole\UserRoleBeanProcessor;

class UserRoleModel extends \Backoffice\Mvc\Base\BaseModel
{

    public function init()
    {
        $this->setFinder(new UserRoleBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new UserRoleBeanProcessor($this->getDbAdpater()));
    }


    public function getRoleList(array $userPermissions): array
    {
        $beanList = $this->getFinder()->getBeanList();
        $existing = $beanList->getData('UserRole_ID');
        $finder = new RoleBeanFinder($this->getDbAdpater());
        $finder->find();
        $RoleList = [];
        foreach ($finder->getBeanList() as $item) {
            $id = $item->getData('UserRole_ID');
            $code = $item->getData('UserRole_Code');
            $permissions = $item->getData('UserPermission_BeanList')->getData('UserPermission_Code');
            $allowed = true;
            foreach ($permissions as $permission) {
                if (!in_array($permission, $userPermissions)) {
                    $allowed = false;
                    break;
                }
            }
            if (!in_array($code, $existing) && $allowed) {
                $RoleList[$id] = $code;
            }
        }
        return $RoleList;
    }
}
