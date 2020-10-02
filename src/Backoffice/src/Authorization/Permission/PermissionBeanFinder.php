<?php


namespace Backoffice\Authorization\Permission;


use Backoffice\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class PermissionBeanFinder extends AbstractBeanFinder
{

    public function __construct(Adapter $adapter)
    {
        parent::__construct(new DatabaseBeanLoader($adapter, 'UserPermission'), new PermissionBeanFactory());
    }

}
