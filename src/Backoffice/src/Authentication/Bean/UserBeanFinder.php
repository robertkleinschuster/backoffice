<?php


namespace Backoffice\Authentication\Bean;


use Backoffice\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;
use Psr\Container\ContainerInterface;

/**
 * Class UserBeanFinder
 * @package Backoffice\Authentication\Bean
 * @method UserBean getBean() : BeanInterface
 * @method UserBeanList getBeanList() : BeanListInterface
 * @method DatabaseBeanLoader getLoader() : BeanLoaderInterface
 */
class UserBeanFinder extends AbstractBeanFinder implements UserRepositoryInterface
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

    /**
     * @param string $credential
     * @param string|null $password
     * @return UserInterface|null
     * @throws \NiceshopsDev\Bean\BeanException
     */
    public function authenticate(string $credential, string $password = null): ?UserInterface
    {
        $this->getLoader()->addWhere('User_Username', $credential);
        $this->find();
        $bean = $this->getBean();
        if (password_verify($password, $bean->getData('User_Password'))) {
            return $bean;
        }
        return null;
    }


}
