<?php


namespace Base\Article\Type;


use NiceshopsDev\Bean\BeanFactory\AbstractBeanFactory;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanList\BeanListInterface;

class ArticleTypeBeanFactory extends AbstractBeanFactory
{
    public function createBean(): BeanInterface
    {
       return new ArticleTypeBean();
    }

    public function createBeanList(): BeanListInterface
    {
        return new ArticleTypeBeanList();
    }

}
