<?php


namespace Backoffice\Authorization\UserRole;


use Backoffice\Authorization\RolePermission\RolePermissionBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class UserRoleBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('UserRole_ID', 'UserRole_ID', 'User_UserRole', 'UserRole_ID', true);
        $loader->addColumn('Person_ID', 'Person_ID', 'User_UserRole', 'UserRole_ID', true);
        $loader->addColumn('UserRole_Code', 'UserRole_Code', 'UserRole', 'UserRole_ID');
        $factory = new UserRoleBeanFactory();
        parent::__construct($loader, $factory);
        $this->linkBeanFinder(new RolePermissionBeanFinder($adapter), 'UserPermission_BeanList', 'UserRole_ID', 'UserRole_ID');
    }

}
