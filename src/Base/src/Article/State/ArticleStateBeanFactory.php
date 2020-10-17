<?php


namespace Base\Article\State;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class ArticleStateBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
        return new ArticleStateBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new ArticleStateBeanList();
    }

}
