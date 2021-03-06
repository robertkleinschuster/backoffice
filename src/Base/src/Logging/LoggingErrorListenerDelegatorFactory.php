<?php


namespace Base\Logging;


use Laminas\Stratigility\Middleware\ErrorHandler;
use Psr\Container\ContainerInterface;

class LoggingErrorListenerDelegatorFactory
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback) : ErrorHandler
    {
        $listener = new LoggingErrorListener($container->get('Logger'));
        $errorHandler = $callback();
        $errorHandler->attachListener($listener);
        return $errorHandler;
    }
}
