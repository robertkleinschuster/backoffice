<?php


namespace Backoffice\Authentication;


use Psr\Container\ContainerInterface;

class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new AuthenticationMiddleware($container);
    }
}
