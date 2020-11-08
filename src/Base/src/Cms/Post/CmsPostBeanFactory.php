<?php

namespace Pars\Base\Cms\Post;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class CmsPostBeanFactory
 * @package Pars\Base\Cms\Post
 */
class CmsPostBeanFactory extends AbstractBeanFactory
{
    function getBeanClass(array $data): string
    {
        return CmsPostBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsPostBeanList::class;
    }


}
