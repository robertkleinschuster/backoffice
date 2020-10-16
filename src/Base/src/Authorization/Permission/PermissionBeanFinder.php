<?php


namespace Base\Authorization\Permission;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class PermissionBeanFinder extends AbstractBeanFinder
{

    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('UserPermission_Code', 'UserPermission_Code', 'UserPermission', 'UserPermission_Code', true);
        $loader->addColumn('UserPermission_Active', 'UserPermission_Active', 'UserPermission', 'UserPermission_Code');
        parent::__construct($loader, new PermissionBeanFactory());
    }

}
