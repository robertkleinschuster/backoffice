<?php


namespace Base\Localization;


use Mvc\Helper\PathHelper;
use Psr\Container\ContainerInterface;

class LocalizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new LocalizationMiddleware($container->get(PathHelper::class));
    }

}
