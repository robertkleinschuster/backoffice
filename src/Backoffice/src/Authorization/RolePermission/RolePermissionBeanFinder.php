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
        $loader = new DatabaseBeanLoader($adapter, 'UserRole_UserPermission');
        $loader->addJoin('UserPermission', 'UserPermission_Code');
        $loader->setFieldColumnMap(
            [
                'UserRole_ID' => 'UserRole_ID',
                'UserPermission_Code' => 'UserPermission_Code',
            ]
        );
        $factory = new RolePermissionBeanFactory();
        parent::__construct($loader, $factory);
    }

}
