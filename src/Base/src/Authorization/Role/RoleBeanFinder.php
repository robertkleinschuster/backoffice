<?php
namespace Base\Authorization\Role;


use Base\Authorization\Permission\PermissionBeanFactory;
use Base\Authorization\Permission\PermissionBeanFinder;
use Base\Authorization\RolePermission\RolePermissionBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;
use NiceshopsDev\Bean\BeanInterface;

class RoleBeanFinder extends AbstractBeanFinder
{

    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('UserRole_ID', 'UserRole_ID', 'UserRole', 'UserRole_ID', true);
        $loader->addColumn('UserRole_Code', 'UserRole_Code', 'UserRole', 'UserRole_ID');
        $loader->addColumn('UserRole_Active', 'UserRole_Active', 'UserRole', 'UserRole_ID');
        parent::__construct($loader, new RoleBeanFactory());
        $this->linkBeanFinder(new RolePermissionBeanFinder($adapter), 'UserPermission_BeanList', 'UserRole_ID', 'UserRole_ID');
    }


}
