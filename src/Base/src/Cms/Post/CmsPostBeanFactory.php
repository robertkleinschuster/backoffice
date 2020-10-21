<?php


namespace Base\Cms\Post;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsPostBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new CmsPostBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsPostBeanList();
    }

}
