<?php


namespace Backoffice\Authorization\Permission;


use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;
use NiceshopsDev\NiceCore\Attribute\AttributeTrait;
use NiceshopsDev\NiceCore\Option\OptionTrait;

class PermissionBeanFactory implements BeanFactoryInterface
{
    use OptionTrait;
    use AttributeTrait;

    public function createBean(): BeanInterface
    {
        return new PermissionBean();
    }

    public function createBeanList(): BeanListInterface
    {
       return new PermissionBeanList();
    }

}
