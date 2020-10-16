<?php


namespace Base\Authorization\UserRole;


use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class UserRoleBeanFactory extends \NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory
{

    /**
     * @inheritDoc
     */
    public function createBean(): BeanInterface
    {
        return new UserRoleBean();
    }

    /**
     * @inheritDoc
     */
    public function createBeanList(): BeanListInterface
    {
        return new UserRoleBeanList();
    }
}
