<?php
namespace Backoffice\Mvc\Setup;


use Backoffice\Authorization\Role\RoleBeanFinder;
use Backoffice\Authorization\Role\RoleBeanProcessor;
use Backoffice\Authorization\UserRole\UserRoleBeanFinder;
use Backoffice\Authorization\UserRole\UserRoleBeanProcessor;
use Mvc\Helper\ValidationHelperAwareInterface;

class SetupModel extends \Backoffice\Mvc\Base\BaseModel
{
    public function init()
    {
        $this->setProcessor(new \Backoffice\Authentication\Bean\UserBeanProcessor($this->getDbAdpater()));
        $this->setFinder(new \Backoffice\Authentication\Bean\UserBeanFinder($this->getDbAdpater()));
    }

    protected function create(array $viewIdMap, array $attributes)
    {

        $schemaUpdater = new \Backoffice\Database\Updater\SchemaUpdater($this->getDbAdpater());
        $schemaUpdater->execute($schemaUpdater->getUpdateMethodList());
        $dataUpdater = new \Backoffice\Database\Updater\DataUpdater($this->getDbAdpater());
        $dataUpdater->execute($dataUpdater->getUpdateMethodList());

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
                $permissionFinder = new \Backoffice\Authorization\Permission\PermissionBeanFinder($this->getDbAdpater());
                $permissionFinder->find();
                $permissionBeanList = $permissionFinder->getBeanList();

                $rolePermissionFinder = new \Backoffice\Authorization\RolePermission\RolePermissionBeanFinder($this->getDbAdpater());
                $rolePermissionBeanList = $rolePermissionFinder->getFactory()->createBeanList();

                foreach ($permissionBeanList as $permission) {
                    $rolePermission = $rolePermissionFinder->getFactory()->createBean();
                    $rolePermission->setData('UserRole_ID', $role->getData('UserRole_ID'));
                    $rolePermission->setData('UserPermission_Code', $permission->getData('UserPermission_Code'));
                    $rolePermissionBeanList->addBean($rolePermission);
                }

                $rolePermissionProcessor = new \Backoffice\Authorization\RolePermission\RolePermissionBeanProcessor($this->getDbAdpater());
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
