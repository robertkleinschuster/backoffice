<?php

namespace Pars\Base\Cms\Menu\Type;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class CmsMenuTypeBeanFactory
 * @package Pars\Base\Cms\Menu\Type
 */
class CmsMenuTypeBeanFactory extends AbstractBeanFactory
{

    protected function getBeanClass(array $data): string
    {
        return CmsMenuTypeBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsMenuTypeBeanList::class;
    }
}
