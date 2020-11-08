<?php

namespace Pars\Base\Authorization\RolePermission;

use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Finder\AbstractBeanFinder;

/**
 * Class RolePermissionBeanFinder
 * @package Pars\Base\Authorization\RolePermission
 */
class RolePermissionBeanFinder extends AbstractBeanFinder
{

    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('UserPermission_Code', 'UserPermission_Code', 'UserRole_UserPermission', 'UserPermission_Code', true);
        $loader->addColumn('UserRole_ID', 'UserRole_ID', 'UserRole_UserPermission', 'UserPermission_Code', true);
        $loader->addColumn('UserPermission_Active', 'UserPermission_Active', 'UserPermission', 'UserPermission_Code');
        $factory = new RolePermissionBeanFactory();
        parent::__construct($loader, $factory);
    }

    /**
     * @param string $userPermission_code
     * @return $this
     */
    public function setUserPersmission_Code(string $userPermission_code): self
    {
        $this->getBeanLoader()->filterValue('UserPermission_Code', $userPermission_code);
        return $this;
    }

    /**
     * @param int $userRole_id
     * @return $this
     */
    public function setUserRole_ID(int $userRole_id): self
    {
        $this->getBeanLoader()->filterValue('UserRole_ID', $userRole_id);
        return $this;
    }


    /**
     * @param bool $userPermission_active
     * @return $this
     */
    public function setUserPermission_Active(bool $userPermission_active): self
    {
        $this->getBeanLoader()->filterValue('UserPermission_Active', $userPermission_active);
        return $this;
    }
}
