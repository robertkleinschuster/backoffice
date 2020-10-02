<?php
namespace Backoffice\Authorization\Role;


use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;
use NiceshopsDev\NiceCore\Attribute\AttributeTrait;
use NiceshopsDev\NiceCore\Option\OptionTrait;

class RoleBeanFactory implements BeanFactoryInterface
{
    use OptionTrait;
    use AttributeTrait;

    public function createBean(): BeanInterface
    {
        return new RoleBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new RoleBeanList();
    }

}
