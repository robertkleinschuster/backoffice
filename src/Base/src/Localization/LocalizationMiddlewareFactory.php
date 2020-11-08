<?php

namespace Pars\Base\Localization;

use Mezzio\Helper\UrlHelper;
use Psr\Container\ContainerInterface;

/**
 * Class LocalizationMiddlewareFactory
 * @package Pars\Base\Localization
 */
class LocalizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new LocalizationMiddleware($container->get(UrlHelper::class));
    }
}
