<?php


namespace Base\Article;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class ArticleBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter, BeanFactoryInterface $beanFactory = null)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('Article_ID', 'Article_ID', 'Article', 'Article_ID', true);
        $loader->addColumn('Article_Code', 'Article_Code', 'Article', 'Article_ID');
        $loader->addColumn('ArticleState_Code', 'ArticleState_Code', 'Article', 'Article_ID');
        $loader->addColumn('ArticleType_Code', 'ArticleType_Code', 'Article', 'Article_ID');
        parent::__construct($loader, $beanFactory ?? new ArticleBeanFactory());
    }

    /**
     * @param string $articleCode
     * @return $this
     */
    public function setArticle_Code(string $articleCode): self
    {
        $this->getLoader()->filterValue('Article_Code', $articleCode);
        return $this;
    }

}
