<?php

namespace Pars\Backoffice\Mvc\UserRole;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Authorization\Role\RoleBeanFinder;
use Pars\Base\Authorization\UserRole\UserRoleBeanFinder;
use Pars\Base\Authorization\UserRole\UserRoleBeanProcessor;

class UserRoleModel extends CrudModel
{

    public function initialize()
    {
        $this->setBeanFinder(new UserRoleBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new UserRoleBeanProcessor($this->getDbAdpater()));
    }


    public function getRoleList(array $userPermissions, array $viewId): array
    {
        $finder = new UserRoleBeanFinder($this->getDbAdpater());
        $finder->getBeanLoader()->initByIdMap($viewId);

        $beanList = $finder->getBeanList();
        $existing = $beanList->getData('UserRole_Code');
        $finder = new RoleBeanFinder($this->getDbAdpater());
        $finder->setUserRole_Active(true);

        $RoleList = [];
        foreach ($finder->getBeanListDecorator() as $item) {
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
