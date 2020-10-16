<?php


namespace Base\Authorization\RolePermission;


use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class RolePermissionBeanFactory extends \NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory
{

    /**
     * @inheritDoc
     */
    public function createBean(): BeanInterface
    {
        return new RolePermissionBean();
    }

    /**
     * @inheritDoc
     */
    public function createBeanList(): BeanListInterface
    {
        return new RolePermissionBeanList();
    }
}
