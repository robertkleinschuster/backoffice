<?php


namespace Base\Cms\Menu;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsMenuBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new CmsMenuBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsMenuBeanList();
    }

}
