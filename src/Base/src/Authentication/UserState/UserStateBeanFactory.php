<?php

namespace Pars\Base\Authentication\UserState;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class UserBeanFactory
 * @package Pars\Base\Authentication\User
 */
class UserStateBeanFactory extends AbstractBeanFactory
{
    protected function getBeanClass(array $data): string
    {
        return UserStateBean::class;
    }

    protected function getBeanListClass(): string
    {
        return UserStateBeanList::class;
    }
}
