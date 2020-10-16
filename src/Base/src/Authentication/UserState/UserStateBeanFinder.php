<?php


namespace Base\Authentication\UserState;


use Base\Authorization\UserRole\UserRoleBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

/**
 * Class UserBeanFinder
 * @package Base\Authentication\User
 * @method UserStateBean getBean() : BeanInterface
 * @method UserStateBeanList getBeanList() : BeanListInterface
 * @method DatabaseBeanLoader getLoader() : BeanLoaderInterface
 */
class UserStateBeanFinder extends AbstractBeanFinder
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
        $loader->addColumn('UserState_Code', 'UserState_Code', 'UserState', 'UserState_Code', true);
        $loader->addColumn('UserState_Active', 'UserState_Active', 'UserState', 'UserState_Code');
        parent::__construct($loader, new UserStateBeanFactory());

    }

}
