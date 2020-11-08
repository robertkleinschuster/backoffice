<?php

namespace Pars\Base\Cms\Post\State;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class CmsPostStateBeanFactory
 * @package Pars\Base\Cms\Post\State
 */
class CmsPostStateBeanFactory extends AbstractBeanFactory
{

    protected function getBeanClass(array $data): string
    {
        return CmsPostStateBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsPostStateBeanList::class;
    }
}
