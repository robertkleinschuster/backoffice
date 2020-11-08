<?php

namespace Pars\Base\Cms\Post\Type;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class CmsPostTypeBeanFactory
 * @package Pars\Base\Cms\Post\Type
 */
class CmsPostTypeBeanFactory extends AbstractBeanFactory
{
    protected function getBeanClass(array $data): string
    {
        return CmsPostTypeBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsPostTypeBeanList::class;
    }
}
