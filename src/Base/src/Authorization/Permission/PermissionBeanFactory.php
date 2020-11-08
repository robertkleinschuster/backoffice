<?php

namespace Pars\Base\Authorization\Permission;

use Niceshops\Bean\Factory\AbstractBeanFactory;

class PermissionBeanFactory extends AbstractBeanFactory
{


    protected function getBeanClass(array $data): string
    {
        return PermissionBean::class;
    }

    protected function getBeanListClass(): string
    {
        return PermissionBeanList::class;
    }
}
