<?php


namespace Backoffice\Authorization\RolePermission;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanFinder\BeanLoaderInterface;

class RolePermissionBeanFinder extends \NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder
{

    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('UserPermission_Code', 'UserPermission_Code', 'UserRole_UserPermission', 'UserPermission_Code', true);
        $loader->addColumn('UserRole_ID', 'UserRole_ID', 'UserRole_UserPermission', 'UserPermission_Code', true);
        $factory = new RolePermissionBeanFactory();
        parent::__construct($loader, $factory);
    }

}
