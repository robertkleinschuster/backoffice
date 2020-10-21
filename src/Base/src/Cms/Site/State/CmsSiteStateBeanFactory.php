<?php


namespace Base\Cms\Site\State;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class CmsSiteStateBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new CmsSiteStateBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new CmsSiteStateBeanList();
    }

}
