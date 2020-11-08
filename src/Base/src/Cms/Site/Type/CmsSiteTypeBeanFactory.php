<?php

namespace Pars\Base\Cms\Site\Type;

use Niceshops\Bean\Factory\AbstractBeanFactory;


class CmsSiteTypeBeanFactory extends AbstractBeanFactory
{
    protected function getBeanClass(array $data): string
    {
        return CmsSiteTypeBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsSiteTypeBeanList::class;
    }
}
