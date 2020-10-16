<?php


namespace Base\Authentication\User;


use Base\Authorization\UserRole\UserRoleBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

/**
 * Class UserBeanFinder
 * @package Base\Authentication\User
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
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('Person_ID', 'Person_ID', 'Person', 'Person_ID', true, null, ['User']);
        $loader->addColumn('Person_Firstname', 'Person_Firstname', 'Person', 'Person_ID');
        $loader->addColumn('Person_Lastname', 'Person_Lastname', 'Person', 'Person_ID');
        $loader->addColumn('User_Username', 'User_Username', 'User', 'Person_ID');
        $loader->addColumn('User_Displayname', 'User_Displayname', 'User', 'Person_ID');
        $loader->addColumn('User_Password', 'User_Password', 'User', 'Person_ID');
        $loader->addColumn('UserState_Code', 'UserState_Code', 'User', 'Person_ID');
        parent::__construct($loader, new UserBeanFactory());
        $userRoleFinder = new UserRoleBeanFinder($adapter);
        $this->linkBeanFinder($userRoleFinder, 'UserRole_BeanList', 'Person_ID', 'Person_ID');
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
        $this->getLoader()->addWhere('UserState_Code', UserBean::STATE_ACTIVE);
        if ($this->find()) {
            $bean = $this->getBean(true);
            if (password_verify($password, $bean->getData('User_Password'))) {
                return $bean;
            }
        }
        return null;
    }


}
