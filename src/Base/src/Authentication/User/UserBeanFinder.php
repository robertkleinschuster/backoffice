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
    private Adapter $adapter;

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
        $userRoleFinder->setUserRole_Active(true);
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
        $this->setUser_Username($credential);
        $this->setUserState_Code(UserBean::STATE_ACTIVE);
        if ($this->find()) {
            $bean = $this->getBean(true);
            if (password_verify($password, $bean->getData('User_Password'))) {
                return $bean;
            }
        }
        return null;
    }

    /**
     * @param int $person_id
     * @param bool $exclude
     * @return $this
     * @throws \Exception
     */
    public function setPerson_ID(int $person_id, bool $exclude = false): self
    {
        if ($exclude) {
            $this->getLoader()->excludeValue('Person_ID', $person_id);
        } else {
            $this->getLoader()->filterValue('Person_ID', $person_id);
        }
        return $this;
    }

    /**
     * @param string $user_username
     * @return $this
     * @throws \Exception
     */
    public function setUser_Username(string $user_username): self
    {
        $this->getLoader()->filterValue('User_Username', $user_username);
        return $this;
    }


    /**
     * @param string $userState_Code
     * @return $this
     * @throws \Exception
     */
    public function setUserState_Code(string $userState_Code): self
    {
        $this->getLoader()->filterValue('UserState_Code', $userState_Code);
        return $this;
    }

}
