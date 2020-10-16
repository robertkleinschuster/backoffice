<?php


namespace Base\Authorization\UserRole;


use Base\Authorization\RolePermission\RolePermissionBeanFinder;
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
        $loader->addColumn('UserRole_Active', 'UserRole_Active', 'UserRole', 'UserRole_ID');
        $factory = new UserRoleBeanFactory();
        parent::__construct($loader, $factory);
        $rolePermissionFinder = new RolePermissionBeanFinder($adapter);
        $rolePermissionFinder->setUserPermission_Active(true);
        $this->linkBeanFinder($rolePermissionFinder, 'UserPermission_BeanList', 'UserRole_ID', 'UserRole_ID');
    }

    /**
     * @param int $person_id
     * @return $this
     * @throws \Exception
     */
    public function setPerson_ID(int $person_id): self
    {
        $this->getLoader()->filterValue('Person_ID', $person_id);
        return $this;
    }

    /**
     * @param string $userRole_id
     * @return $this
     */
    public function setUserRole_ID(int $userRole_id): self
    {
        $this->getLoader()->filterValue('UserRole_ID', $userRole_id);
        return $this;
    }

    /**
     * @param string $userRole_code
     * @return $this
     */
    public function setUserRole_Code(string $userRole_code): self {
        $this->getLoader()->filterValue('UserRole_Code', $userRole_code);
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
