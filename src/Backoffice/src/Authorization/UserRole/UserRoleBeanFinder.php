<?php


namespace Backoffice\Authorization\UserRole;


use Backoffice\Authorization\RolePermission\RolePermissionBeanFinder;
use Backoffice\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class UserRoleBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter, 'User_UserRole');
        $loader->addJoin('UserRole', 'UserRole_ID');
        $loader->setFieldColumnMap([
            'Person_ID' => 'Person_ID',
            'UserRole_ID' => 'UserRole_ID',
            'UserRole_Code' => 'UserRole_Code',
        ]);
        $loader->addSelect('Person_ID');
        $loader->addSelect('UserRole_ID');
        $loader->addSelect('UserRole_Code', 'UserRole');
        $factory = new UserRoleBeanFactory();
        parent::__construct($loader, $factory);
        $this->linkBeanFinder(new RolePermissionBeanFinder($adapter), 'UserPermission_BeanList', 'UserRole_ID', 'UserRole_ID');
    }

}
