<?php
namespace Backoffice\Authorization\Role;


use Backoffice\Authorization\Permission\PermissionBeanFactory;
use Backoffice\Authorization\Permission\PermissionBeanFinder;
use Backoffice\Authorization\RolePermission\RolePermissionBeanFinder;
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
        $loader = new DatabaseBeanLoader($adapter, 'UserRole');
        $loader->setFieldColumnMap([
           'UserRole_ID' => 'UserRole_ID',
           'UserRole_Code' => 'UserRole_Code',
           'UserRole_Active' => 'UserRole_Active'
        ]);
        parent::__construct($loader, new RoleBeanFactory());
        $this->linkBeanFinder(new RolePermissionBeanFinder($adapter), 'UserPermission_BeanList', 'UserRole_ID', 'UserRole_ID');
    }


}
