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
        $rolePermissionFinder = new RolePermissionBeanFinder($adapter);
        $rolePermissionFinder->setUserPermission_Active(true);
        $this->linkBeanFinder($rolePermissionFinder, 'UserPermission_BeanList', 'UserRole_ID', 'UserRole_ID');
    }

    /**
     * @param int $userRole_id
     * @param bool $exclude
     * @return $this
     */
    public function setUserRole_ID(int $userRole_id, bool $exclude = false): self {
        if ($exclude) {
            $this->getLoader()->excludeValue('UserRole_ID', $userRole_id);
        } else {
            $this->getLoader()->filterValue('UserRole_ID', $userRole_id);
        }
        return $this;
    }

    /**
     * @param string $userRole_code
     * @param bool $exclude
     * @return $this
     */
    public function setUserRole_Code(string $userRole_code, bool $exclude = false): self {
        if ($exclude) {
            $this->getLoader()->excludeValue('UserRole_Code', $userRole_code);
        } else {
            $this->getLoader()->filterValue('UserRole_Code', $userRole_code);
        }
        return $this;
    }


    /**
     * @param string $userRole_active
     * @return $this
     */
    public function setUserRole_Active(bool $userRole_active): self {
        $this->getLoader()->filterValue('UserRole_Active', $userRole_active);
        return $this;
    }



}
