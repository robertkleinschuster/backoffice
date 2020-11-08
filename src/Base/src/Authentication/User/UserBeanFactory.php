<?php

namespace Pars\Base\Authentication\User;

use Pars\Base\Authorization\UserRole\UserRoleBeanList;
use Mezzio\Authentication\UserInterface;
use Niceshops\Bean\Factory\AbstractBeanFactory;
use Psr\Container\ContainerInterface;

/**
 * Class UserBeanFactory
 * @package Pars\Base\Authentication\User
 * @method UserBean getEmptyBean(array $data) : BeanInterface
 */
class UserBeanFactory extends AbstractBeanFactory
{

    public function __invoke(ContainerInterface $container): callable
    {
        return function (string $identity, array $roles = [], array $details = []): UserInterface {
            $bean = $this->getEmptyBean($details);
            $bean->setSerializeData($details);
            $roleBeanList = new UserRoleBeanList();
            $roleBeanList->setSerializeData($roles);
            $bean->setData('UserRole_BeanList', $roleBeanList);
            return $bean;
        };
    }


    protected function getBeanClass(array $data): string
    {
        return UserBean::class;
    }

    protected function getBeanListClass(): string
    {
        return UserBeanList::class;
    }
}
