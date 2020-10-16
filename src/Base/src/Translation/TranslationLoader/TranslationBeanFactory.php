<?php


namespace Base\Translation\TranslationLoader;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class TranslationBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new TranslationBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new TranslationBeanList();
    }

}
