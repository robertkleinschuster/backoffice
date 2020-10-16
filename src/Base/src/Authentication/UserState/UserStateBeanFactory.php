<?php


namespace Base\Authentication\UserState;

use Base\Authorization\UserRole\UserRoleBeanList;
use Mezzio\Authentication\UserInterface;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;
use NiceshopsDev\NiceCore\Attribute\AttributeTrait;
use NiceshopsDev\NiceCore\Option\OptionTrait;
use Psr\Container\ContainerInterface;

/**
 * Class UserBeanFactory
 * @package Base\Authentication\User
 */
class UserStateBeanFactory implements BeanFactoryInterface
{
    use OptionTrait;
    use AttributeTrait;


    /**
     * @return UserBean
     */
    public function createBean(): BeanInterface
    {
        return new UserStateBean();
    }

    /**
     * @return UserBeanList
     */
    public function createBeanList(): BeanListInterface
    {
        return new UserStateBeanList();
    }


}
