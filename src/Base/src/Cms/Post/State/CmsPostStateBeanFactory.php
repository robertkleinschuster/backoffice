<?php


namespace Base\Cms\Post\State;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsPostStateBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new CmsPostStateBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsPostStateBeanList();
    }

}
