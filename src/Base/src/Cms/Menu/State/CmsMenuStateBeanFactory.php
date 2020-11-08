<?php

namespace Pars\Base\Cms\Menu\State;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class CmsMenuStateBeanFactory
 * @package Pars\Base\Cms\Menu\State
 */
class CmsMenuStateBeanFactory extends AbstractBeanFactory
{

    protected function getBeanClass(array $data): string
    {
        return CmsMenuStateBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsMenuStateBeanList::class;
    }
}
