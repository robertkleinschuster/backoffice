<?php


namespace Base\Cms\Menu\Type;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsMenuTypeBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
       return new CmsMenuTypeBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsMenuTypeBeanList();
    }

}
