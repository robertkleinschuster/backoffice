<?php

namespace Backoffice\Mvc\Setup;


use Base\Authorization\Role\RoleBeanFinder;
use Base\Authorization\Role\RoleBeanProcessor;
use Base\Authorization\UserRole\UserRoleBeanFinder;
use Base\Authorization\UserRole\UserRoleBeanProcessor;

class SetupModel extends \Backoffice\Mvc\Base\BaseModel
{
    public function init()
    {
        $this->setProcessor(new \Base\Authentication\User\UserBeanProcessor($this->getDbAdpater()));
        $this->setFinder(new \Base\Authentication\User\UserBeanFinder($this->getDbAdpater()));
    }

    protected function create(array $viewIdMap, array $attributes)
    {
        $schemaUpdater = new \Base\Database\Updater\SchemaUpdater($this->getDbAdpater());
        $methods = [];
        foreach ($schemaUpdater->getUpdateMethodList() as $method) {
            $methods[$method] = true;
        }
        $result = $schemaUpdater->execute($methods);

        $dataUpdater = new \Base\Database\Updater\DataUpdater($this->getDbAdpater());
        $methods = [];
        foreach ($dataUpdater->getUpdateMethodList() as $method) {
            $methods[$method] = true;
        }
        $result = $dataUpdater->execute($methods);


        parent::create($viewIdMap, $attributes);

        if ($this->getFinder()->find() == 1) {
            $user = $this->getFinder()->getBean();

            $roleFinder = new RoleBeanFinder($this->getDbAdpater());
            $role = $roleFinder->getFactory()->createBean();
            $role->setData('UserRole_Code', 'admin');
            $roleList = $roleFinder->getFactory()->createBeanList();
            $roleList->addBean($role);

            $roleProcessor = new RoleBeanProcessor($this->getDbAdpater());
            $roleProcessor->setBeanList($roleList);
            $roleProcessor->save();

            if ($roleFinder->find() == 1) {
                $role = $roleFinder->getBean();
                $permissionFinder = new \Base\Authorization\Permission\PermissionBeanFinder($this->getDbAdpater());
                $permissionFinder->find();
                $permissionBeanList = $permissionFinder->getBeanList();

                $rolePermissionFinder = new \Base\Authorization\RolePermission\RolePermissionBeanFinder($this->getDbAdpater());
                $rolePermissionBeanList = $rolePermissionFinder->getFactory()->createBeanList();

                foreach ($permissionBeanList as $permission) {
                    $rolePermission = $rolePermissionFinder->getFactory()->createBean();
                    $rolePermission->setData('UserRole_ID', $role->getData('UserRole_ID'));
                    $rolePermission->setData('UserPermission_Code', $permission->getData('UserPermission_Code'));
                    $rolePermissionBeanList->addBean($rolePermission);
                }

                $rolePermissionProcessor = new \Base\Authorization\RolePermission\RolePermissionBeanProcessor($this->getDbAdpater());
                $rolePermissionProcessor->setBeanList($rolePermissionBeanList);
                $rolePermissionProcessor->save();

                $userRoleFinder = new UserRoleBeanFinder($this->getDbAdpater());
                $userRole = $userRoleFinder->getFactory()->createBean();
                $userRoleList = $userRoleFinder->getFactory()->createBeanList();
                $userRole->setData('Person_ID', $user->getData('Person_ID'));
                $userRole->setData('UserRole_ID', $role->getData('UserRole_ID'));
                $userRoleList->addBean($userRole);

                $userRoleProcessor = new UserRoleBeanProcessor($this->getDbAdpater());
                $userRoleProcessor->setBeanList($userRoleList);
                $userRoleProcessor->save();
            }
        }
    }


}
