<?php

namespace Pars\Base\Translation;

use Laminas\I18n\Translator\TranslatorInterface;
use Psr\Container\ContainerInterface;

/**
 * Class TranslatorMiddlewareFactory
 * @package Pars\Base\Translation
 */
class TranslatorMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new TranslatorMiddleware($container->get(TranslatorInterface::class));
    }
}
