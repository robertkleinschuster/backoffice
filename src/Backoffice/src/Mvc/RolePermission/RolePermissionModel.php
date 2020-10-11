<?php
namespace Backoffice\Mvc\RolePermission;


use Backoffice\Authorization\Permission\PermissionBeanFinder;
use Backoffice\Authorization\RolePermission\RolePermissionBeanFinder;
use Backoffice\Authorization\RolePermission\RolePermissionBeanProcessor;
use Backoffice\Mvc\Base\BaseModel;

class RolePermissionModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new RolePermissionBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new RolePermissionBeanProcessor($this->getDbAdpater()));
    }


    public function getPermissionList(array $userPermissions): array
    {
        $beanList = $this->getFinder()->getBeanGenerator();
        $existing = $beanList->getData('UserPermission_Code');
        $finder = new PermissionBeanFinder($this->getDbAdpater());
        $finder->find();
        $permissionList = [];
        foreach ($finder->getBeanGenerator() as $item) {
            $code = $item->getData('UserPermission_Code');
            if (!in_array($code, $existing) && in_array($code, $userPermissions)) {
                $permissionList[$code] = $code;
            }
        }
        return $permissionList;
    }
}
