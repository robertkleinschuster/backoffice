<?php

namespace Pars\Base\Cms\SiteParagraph;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class CmsSiteParagraphBeanFactory
 * @package Pars\Base\Cms\SiteParagraph
 */
class CmsSiteParagraphBeanFactory extends AbstractBeanFactory
{

    protected function getBeanClass(array $data): string
    {
        return CmsSiteParagraphBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsSiteParagraphBeanList::class;
    }

}
