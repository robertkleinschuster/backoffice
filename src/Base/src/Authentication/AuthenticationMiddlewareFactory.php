<?php

namespace Pars\Base\Authentication;

use Psr\Container\ContainerInterface;

/**
 * Class AuthenticationMiddlewareFactory
 * @package Pars\Base\Authentication
 */
class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new AuthenticationMiddleware($container);
    }
}
