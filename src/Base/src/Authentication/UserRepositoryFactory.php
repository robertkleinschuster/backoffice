<?php

namespace Pars\Base\Authentication;

use Pars\Base\Authentication\User\UserBeanFinder;
use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

/**
 * Class UserRepositoryFactory
 * @package Pars\Base\Authentication
 */
class UserRepositoryFactory
{
    /**
     * @param ContainerInterface $container
     * @return UserBeanFinder
     */
    public function __invoke(ContainerInterface $container)
    {
        return new UserBeanFinder($container->get(AdapterInterface::class));
    }
}
