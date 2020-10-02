<?php
namespace Backoffice\Authorization\Role;


use Backoffice\Authorization\Permission\PermissionBeanFactory;
use Backoffice\Authorization\Permission\PermissionBeanFinder;
use Backoffice\Database\DatabaseBeanLoader;
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
    }

    protected function initializeBeanWithAdditionlData(BeanInterface $bean): BeanInterface
    {
        $bean = parent::initializeBeanWithAdditionlData($bean);
        $permissionFinder = new PermissionBeanFinder($this->adapter);
        $permissionFinder->getLoader()->addJoin('UserRole_UserPermission', 'UserPermission_Code');
        $permissionFinder->getLoader()->addWhere('UserRole_ID', $bean->getData('UserRole_ID'), 'UserRole_UserPermission');
        if ($permissionFinder->find()) {
            $beanList = $permissionFinder->getBeanList();
        } else {
            $beanList = $permissionFinder->getFactory()->createBeanList();
        }
        $bean->setData('Permissions',  $beanList->getData('UserPermission_Code'));
        $bean->setData('UserPermission_BeanList',  $beanList);
        return $bean;
    }


}
