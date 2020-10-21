<?php


namespace Base\Cms\Post\Type;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsPostTypeBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
       return new CmsPostTypeBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsPostTypeBeanList();
    }

}
