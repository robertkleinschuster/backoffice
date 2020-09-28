<?php


namespace Backoffice\Authentication\Bean;


use Backoffice\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

/**
 * Class UserBeanFinder
 * @package Backoffice\Authentication\Bean
 * @method UserBean getBean() : BeanInterface
 * @method UserBeanList getBeanList() : BeanListInterface
 * @method DatabaseBeanLoader getLoader() : BeanLoaderInterface
 */
class UserBeanFinder extends AbstractBeanFinder
{

    /**
     * UserBeanFinder constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter, 'User');
        $loader->addJoin('Person', 'Person_ID');
        parent::__construct($loader, new UserBeanFactory());
    }
}
