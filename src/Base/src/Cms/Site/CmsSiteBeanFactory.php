<?php


namespace Base\Cms\Site;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsSiteBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new CmsSiteBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsSiteBeanList();
    }

}
