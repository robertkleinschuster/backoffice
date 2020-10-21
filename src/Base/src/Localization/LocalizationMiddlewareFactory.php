<?php


namespace Base\Localization;


use Mezzio\Helper\UrlHelper;
use Mvc\Helper\PathHelper;
use Psr\Container\ContainerInterface;

class LocalizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new LocalizationMiddleware($container->get(UrlHelper::class));
    }

}
