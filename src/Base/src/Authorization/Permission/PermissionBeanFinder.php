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

    /**
     * @param string $userPermission_code
     * @return $this
     */
    public function setUserPermission_Code(string $userPermission_code): self {
        $this->getLoader()->filterValue('UserPermission_Code', $userPermission_code);
        return $this;
    }

    /**
     * @param string $userPermission_active
     * @return $this
     */
    public function setUserPermission_Active(bool $userPermission_active): self {
        $this->getLoader()->filterValue('UserPermission_Active', $userPermission_active);
        return $this;
    }

}
