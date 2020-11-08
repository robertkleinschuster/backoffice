<?php

namespace Pars\Backoffice\Mvc\RolePermission;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Authorization\Permission\PermissionBeanFinder;
use Pars\Base\Authorization\RolePermission\RolePermissionBeanFinder;
use Pars\Base\Authorization\RolePermission\RolePermissionBeanProcessor;
use Pars\Mvc\Parameter\IdParameter;

class RolePermissionModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new RolePermissionBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new RolePermissionBeanProcessor($this->getDbAdpater()));
    }


    public function getPermissionList(array $userPermissions, IdParameter $idParameter): array
    {
        $finder = new RolePermissionBeanFinder($this->getDbAdpater());
        $finder->getBeanLoader()->initByIdMap($idParameter->getAttribute_List());

        $beanList = $finder->getBeanList();
        $existing = $beanList->getData('UserPermission_Code');
        $finder = new PermissionBeanFinder($this->getDbAdpater());
        $finder->setUserPermission_Active(true);

        $permissionList = [];
        $beanList = $finder->getBeanList();
        foreach ($beanList as $item) {
            $code = $item->getData('UserPermission_Code');
            if (!in_array($code, $existing) && in_array($code, $userPermissions)) {
                $permissionList[$code] = $code;
            }
        }
        return $permissionList;
    }
}
