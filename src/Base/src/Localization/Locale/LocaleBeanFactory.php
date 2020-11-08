<?php

namespace Pars\Base\Localization\Locale;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class LocaleBeanFactory
 * @package Pars\Base\Localization\Locale
 */
class LocaleBeanFactory extends AbstractBeanFactory
{
    protected function getBeanClass(array $data): string
    {
        return LocaleBean::class;
    }

    protected function getBeanListClass(): string
    {
        return LocaleBeanList::class;
    }
}
