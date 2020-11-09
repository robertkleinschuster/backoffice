<?php

namespace Pars\Backoffice\Mvc\Setup;

use Pars\Backoffice\Mvc\Base\BackofficeBeanConverter;
use Pars\Base\Authorization\Permission\PermissionBeanFinder;
use Pars\Base\Authorization\Role\RoleBeanFinder;
use Pars\Base\Authorization\Role\RoleBeanProcessor;
use Pars\Base\Authorization\RolePermission\RolePermissionBeanFinder;
use Pars\Base\Authorization\RolePermission\RolePermissionBeanProcessor;
use Pars\Base\Authorization\UserRole\UserRoleBeanFinder;
use Pars\Base\Authorization\UserRole\UserRoleBeanProcessor;
use Pars\Mvc\Parameter\IdParameter;

/**
 * Class SetupModel
 * @package Pars\Backoffice\Mvc\Setup
 */
class SetupModel extends \Pars\Backoffice\Mvc\Base\BaseModel
{
    public function initialize()
    {
        $this->setBeanProcessor(new \Pars\Base\Authentication\User\UserBeanProcessor($this->getDbAdpater()));
        $this->setBeanFinder(new \Pars\Base\Authentication\User\UserBeanFinder($this->getDbAdpater()));
    }

    protected function create(IdParameter $idParameter, array $attributes): void
    {
        $schemaUpdater = new \Pars\Base\Database\Updater\SchemaUpdater($this->getDbAdpater());
        $methods = [];
        foreach ($schemaUpdater->getUpdateMethodList() as $method) {
            $methods[$method] = true;
        }
        $result = $schemaUpdater->execute($methods);

        $dataUpdater = new \Pars\Base\Database\Updater\DataUpdater($this->getDbAdpater());
        $methods = [];
        foreach ($dataUpdater->getUpdateMethodList() as $method) {
            $methods[$method] = true;
        }
        $result = $dataUpdater->execute($methods);


        parent::create($idParameter, $attributes);

        if ($this->getBeanFinder()->count() == 1) {
            $user = $this->getBeanFinder()->getBean();

            $roleFinder = new RoleBeanFinder($this->getDbAdpater());
            $role = $roleFinder->getBeanFactory()->getEmptyBean([]);
            $role->setData('UserRole_Code', 'admin');
            $roleList = $roleFinder->getBeanFactory()->getEmptyBeanList();
            $roleList->addBean($role);

            $roleProcessor = new RoleBeanProcessor($this->getDbAdpater());
            $roleProcessor->setBeanList($roleList);
            $roleProcessor->save();

            if ($roleFinder->count() == 1) {
                $role = $roleFinder->getBean();
                $permissionFinder = new PermissionBeanFinder($this->getDbAdpater());
                $permissionBeanList = $permissionFinder->getBeanList();

                $rolePermissionFinder = new RolePermissionBeanFinder($this->getDbAdpater());
                $rolePermissionBeanList = $rolePermissionFinder->getBeanFactory()->getEmptyBeanList();

                foreach ($permissionBeanList as $permission) {
                    $rolePermission = $rolePermissionFinder->getBeanFactory()->getEmptyBean([]);
                    $rolePermission->setData('UserRole_ID', $role->getData('UserRole_ID'));
                    $rolePermission->setData('UserPermission_Code', $permission->getData('UserPermission_Code'));
                    $rolePermissionBeanList->addBean($rolePermission);
                }

                $rolePermissionProcessor = new RolePermissionBeanProcessor($this->getDbAdpater());
                $rolePermissionProcessor->setBeanList($rolePermissionBeanList);
                $rolePermissionProcessor->save();

                $userRoleFinder = new UserRoleBeanFinder($this->getDbAdpater());
                $userRole = $userRoleFinder->getBeanFactory()->getEmptyBean([]);
                $userRoleList = $userRoleFinder->getBeanFactory()->getEmptyBeanList();
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
