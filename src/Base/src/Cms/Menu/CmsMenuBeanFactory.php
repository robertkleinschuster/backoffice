<?php

namespace Pars\Base\Cms\Menu;



use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class CmsMenuBeanFactory
 * @package Pars\Base\Cms\Menu
 */
class CmsMenuBeanFactory extends AbstractBeanFactory
{


    protected function getBeanClass(array $data): string
    {
       return CmsMenuBean::class;
    }

    protected function getBeanListClass(): string
    {
        return CmsMenuBeanList::class;
    }


}
