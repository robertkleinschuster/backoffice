<?php


namespace Base\Article\Translation;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class ArticleTranslationBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new ArticleTranslationBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new ArticleTranslationBeanList();
    }

}
