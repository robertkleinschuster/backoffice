<?php


namespace Backoffice\Authentication\Bean;


use Backoffice\Authorization\Role\RoleBean;
use Backoffice\Authorization\Role\RoleBeanList;
use Mezzio\Authentication\UserInterface;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;
use NiceshopsDev\NiceCore\Attribute\AttributeTrait;
use NiceshopsDev\NiceCore\Option\OptionTrait;
use Psr\Container\ContainerInterface;

/**
 * Class UserBeanFactory
 * @package Backoffice\Authentication\Bean
 */
class UserBeanFactory implements BeanFactoryInterface
{
    use OptionTrait;
    use AttributeTrait;


    public function __invoke(ContainerInterface $container) : callable
    {
        return function (string $identity, array $roles = [], array $details = []) : UserInterface {
            $bean = $this->createBean();
            $bean->setFromArray($details);
            $roleBeanList = new RoleBeanList();
            $roleBeanList->setSerializeData($roles);
            $bean->setData('UserRole_BeanList', $roleBeanList);
            return $bean;
        };
    }

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
