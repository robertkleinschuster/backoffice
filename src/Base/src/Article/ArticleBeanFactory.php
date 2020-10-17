<?php


namespace Base\Article;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class ArticleBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new ArticleBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new ArticleBeanList();
    }

}
