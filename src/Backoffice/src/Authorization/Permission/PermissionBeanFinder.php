<?php


namespace Backoffice\Authorization\Permission;


use Backoffice\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class PermissionBeanFinder extends AbstractBeanFinder
{

    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter, 'UserPermission');
        $loader->setFieldColumnMap([
            'UserPermission_Code' => 'UserPermission_Code',
            'UserPermission_Active' => 'UserPermission_Active',
        ]);
        parent::__construct($loader, new PermissionBeanFactory());
    }

}
