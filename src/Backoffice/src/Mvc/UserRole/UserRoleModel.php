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


    public function getRoleList(): array
    {
        $beanList = $this->getFinder()->getBeanList();
        $existing = $beanList->getData('UserRole_ID');
        $finder = new RoleBeanFinder($this->getDbAdpater());
        $finder->find();
        $RoleList = [];
        foreach ($finder->getBeanList() as $item) {
            $id = $item->getData('UserRole_ID');
            $code = $item->getData('UserRole_Code');
            if (!in_array($code, $existing)) {
                $RoleList[$id] = $code;
            }
        }
        return $RoleList;
    }
}
