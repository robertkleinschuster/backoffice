<?php


namespace Backoffice\Authentication\Bean;


use Backoffice\Authorization\Role\RoleBeanFinder;
use Backoffice\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;
use NiceshopsDev\Bean\BeanInterface;

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
     * @var Adapter
     */
    private $adapter;

    /**
     * UserBeanFinder constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
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
        if ($this->find()) {
            $bean = $this->getBean();
            if (password_verify($password, $bean->getData('User_Password'))) {
                return $bean;
            }
        }
        return null;
    }

    /**
     * @param BeanInterface $bean
     * @return BeanInterface
     */
    protected function initializeBeanWithAdditionlData(BeanInterface $bean): BeanInterface
    {
        $bean =  parent::initializeBeanWithAdditionlData($bean);
        $roleFinder = new RoleBeanFinder($this->adapter);
        $roleFinder->getLoader()->addJoin('User_UserRole', 'UserRole_Code');
        $roleFinder->getLoader()->addWhere('Person_ID', $bean->getData('Person_ID'), 'User_UserRole');
        if ($roleFinder->find()) {
            $beanList = $roleFinder->getBeanList();
        } else {
            $beanList = $roleFinder->getFactory()->createBeanList();
        }
        $bean->setData('Roles',  $beanList->getData('UserRole_Code'));
        return $bean;
    }


}
