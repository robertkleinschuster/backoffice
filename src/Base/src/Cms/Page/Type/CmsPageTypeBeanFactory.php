<?php

namespace Pars\Base\Cms\Page\Type;

use Niceshops\Bean\Factory\AbstractBeanFactory;


class CmsPageTypeBeanFactory extends AbstractBeanFactory
{
    protected function getBeanClass(array $data): string
    {
        return CmsPageTypeBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsPageTypeBeanList::class;
    }
}
