<?php


namespace Base\Authentication\Bean;


use Backoffice\Authorization\UserRole\UserRoleBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

/**
 * Class UserBeanFinder
 * @package Base\Authentication\Bean
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
        $loader->setFieldColumnMap([
            'Person_ID' => 'Person_ID',
            'Person_Firstname' => 'Person_Firstname',
            'Person_Lastname' => 'Person_Lastname',
            'User_Username' => 'User_Username',
            'User_Displayname' => 'User_Displayname',
            'User_Password' => 'User_Password',
            'UserState_Code' => 'UserState_Code',
        ]);
        $loader->addSelect('Person_ID', 'Person');
        $loader->addSelect('Person_Firstname', 'Person');
        $loader->addSelect('Person_Lastname', 'Person');
        $loader->addSelect('User_Username');
        $loader->addSelect('User_Displayname');
        $loader->addSelect('User_Password');
        $loader->addSelect('UserState_Code');
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
        if ($this->find()) {
            $bean = $this->getBean();
            if (password_verify($password, $bean->getData('User_Password'))) {
                return $bean;
            }
        }
        return null;
    }


}
