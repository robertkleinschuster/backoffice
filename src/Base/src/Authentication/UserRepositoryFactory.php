<?php

namespace Base\Authentication;

use Base\Authentication\User\UserBeanFinder;
use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

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
