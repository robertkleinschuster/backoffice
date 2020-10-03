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

    protected function delete(array $viewIdMap)
    {
        $this->getProcessor()->getSaver()->setPrimaryKeyList([
            'Person_ID',
            'UserRole_ID'
        ]);
        parent::delete($viewIdMap);
    }


    public function getPermissionList(): array
    {
        $beanList = $this->getFinder()->getBeanList();
        $existing = $beanList->getData('UserPermission_Code');
        $finder = new PermissionBeanFinder($this->getDbAdpater());
        $finder->find();
        $permissionList = [];
        foreach ($finder->getBeanList() as $item) {
            $code = $item->getData('UserPermission_Code');
            if (!in_array($code, $existing)) {
                $permissionList[$code] = $code;
            }
        }
        return $permissionList;
    }
}
