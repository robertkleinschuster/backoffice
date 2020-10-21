<?php


namespace Base\Cms\Menu\State;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsMenuStateBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new CmsMenuStateBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsMenuStateBeanList();
    }

}
