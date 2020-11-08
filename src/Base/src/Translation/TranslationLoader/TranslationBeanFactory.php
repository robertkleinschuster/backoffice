<?php

namespace Pars\Base\Translation\TranslationLoader;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class TranslationBeanFactory
 * @package Pars\Base\Translation\TranslationLoader
 */
class TranslationBeanFactory extends AbstractBeanFactory
{

    protected function getBeanClass(array $data): string
    {
        return TranslationBean::class;
    }

    protected function getBeanListClass(): string
    {
        return TranslationBeanList::class;
    }
}
