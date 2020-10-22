<?php
namespace Backoffice\Mvc\RolePermission;


use Base\Authorization\Permission\PermissionBeanFinder;
use Base\Authorization\RolePermission\RolePermissionBeanFinder;
use Base\Authorization\RolePermission\RolePermissionBeanProcessor;
use Backoffice\Mvc\Base\BaseModel;

class RolePermissionModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new RolePermissionBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new RolePermissionBeanProcessor($this->getDbAdpater()));
    }


    public function getPermissionList(array $userPermissions, array $viewId): array
    {
        $finder = new RolePermissionBeanFinder($this->getDbAdpater());
        $finder->getLoader()->initByIdMap($viewId);
        $finder->find();
        $beanList = $finder->getBeanList();
        $existing = $beanList->getData('UserPermission_Code');
        $finder = new PermissionBeanFinder($this->getDbAdpater());
        $finder->setUserPermission_Active(true);
        $finder->find();
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
