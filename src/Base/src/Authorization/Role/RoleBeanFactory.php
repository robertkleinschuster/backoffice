<?php

namespace Pars\Base\Authorization\Role;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class RoleBeanFactory
 * @package Pars\Base\Authorization\Role
 */
class RoleBeanFactory extends AbstractBeanFactory
{

    protected function getBeanClass(array $data): string
    {
        return RoleBean::class;
    }

    protected function getBeanListClass(): string
    {
        return RoleBeanList::class;
    }
}
