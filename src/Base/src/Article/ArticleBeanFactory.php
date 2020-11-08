<?php

namespace Pars\Base\Article;

use Niceshops\Bean\Factory\AbstractBeanFactory;

/**
 * Class ArticleBeanFactory
 * @package Pars\Base\Article
 */
class ArticleBeanFactory extends AbstractBeanFactory
{

    protected function getBeanClass(array $data): string
    {
        return ArticleBean::class;
    }

    protected function getBeanListClass(): string
    {
        return ArticleBeanList::class;
    }
}
