<?php


namespace Base\Article;


use Base\Article\Translation\ArticleTranslationBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class ArticleBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('Article_ID', 'Article_ID', 'Article', 'Article_ID', true);
        $loader->addColumn('Article_Code', 'Article_Code', 'Article', 'Article_ID');
        $loader->addColumn('ArticleState_Code', 'ArticleState_Code', 'Article', 'Article_ID');
        $loader->addColumn('ArticleType_Code', 'ArticleType_Code', 'Article', 'Article_ID');
        parent::__construct($loader, new ArticleBeanFactory());
    }


}
