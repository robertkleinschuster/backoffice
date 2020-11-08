<?php

namespace Pars\Base\Cms\Site;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class CmsSiteBeanFactory
 * @package Pars\Base\Cms\Site
 */
class CmsSiteBeanFactory extends AbstractBeanFactory
{

    protected function getBeanClass(array $data): string
    {
        return CmsSiteBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsSiteBeanList::class;
    }
}
