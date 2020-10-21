<?php


namespace Base\Cms\Site\Type;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsSiteTypeBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
       return new CmsSiteTypeBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsSiteTypeBeanList();
    }

}
