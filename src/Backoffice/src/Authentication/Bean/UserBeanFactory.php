<?php


namespace Backoffice\Authentication\Bean;


use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;
use NiceshopsDev\NiceCore\Attribute\AttributeTrait;
use NiceshopsDev\NiceCore\Option\OptionTrait;

/**
 * Class UserBeanFactory
 * @package Backoffice\Authentication\Bean
 */
class UserBeanFactory implements BeanFactoryInterface
{
    use OptionTrait;
    use AttributeTrait;

    /**
     * @return UserBean
     */
    public function createBean(): BeanInterface
    {
        return new UserBean();
    }

    /**
     * @return UserBeanList
     */
    public function createBeanList(): BeanListInterface
    {
        return new UserBeanList();
    }


}
