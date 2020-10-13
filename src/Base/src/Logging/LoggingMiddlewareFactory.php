<?php


namespace Base\Logging;


use Psr\Container\ContainerInterface;

class LoggingMiddlewareFactory
{

    /**
     * @param ContainerInterface $container
     * @return LoggingMiddleware
     */
    public function __invoke(ContainerInterface $container)
    {
        return new LoggingMiddleware($container->get('Logger'));
    }

}
