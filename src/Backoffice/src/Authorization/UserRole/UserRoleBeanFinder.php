<?php


namespace Backoffice\Mvc\Authorization\UserRole;


use Backoffice\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class UserRoleBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter, 'User_UserRole');
        $loader->setFieldColumnMap([
            'Person_ID' => 'Person_ID',
            'UserRole_ID' => 'UserRole_ID',
        ]);
        $factory = new UserRoleBeanFactory();
        parent::__construct($loader, $factory);
    }

}
