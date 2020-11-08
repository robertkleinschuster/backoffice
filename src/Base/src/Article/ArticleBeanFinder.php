<?php

namespace Pars\Base\Article;

use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Factory\BeanFactoryInterface;
use Niceshops\Bean\Finder\AbstractBeanFinder;

/**
 * Class ArticleBeanFinder
 * @package Pars\Base\Article
 */
class ArticleBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter, BeanFactoryInterface $beanFactory = null)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('Article_ID', 'Article_ID', 'Article', 'Article_ID', true);
        $loader->addColumn('Article_Code', 'Article_Code', 'Article', 'Article_ID');
        parent::__construct($loader, $beanFactory ?? new ArticleBeanFactory());
    }

    /**
     * @param string $articleCode
     * @return $this
     */
    public function setArticle_Code(string $articleCode): self
    {
        $this->getBeanLoader()->filterValue('Article_Code', $articleCode);
        return $this;
    }
    /**
     * @param int $article_id
     * @param bool $exclude
     * @return $this
     * @throws \Exception
     */
    public function setArticle_ID(int $article_id, bool $exclude = false): self
    {
        if ($exclude) {
            $this->getBeanLoader()->excludeValue('Article_ID', $article_id);
        } else {
            $this->getBeanLoader()->filterValue('Article_ID', $article_id);
        }
        return $this;
    }
}
