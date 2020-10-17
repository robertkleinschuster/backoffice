<?php


namespace Base\Article\Type;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class ArticleTypeBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('ArticleType_Code', 'ArticleType_Code', 'ArticleType', 'ArticleType_Code', true);
        $loader->addColumn('ArticleType_Active', 'ArticleType_Active', 'ArticleType', 'ArticleType_Code');
        parent::__construct($loader, new ArticleTypeBeanFactory());
    }

    public function setArticleType_Active(bool $active): self
    {
        $this->getLoader()->filterValue('ArticleType_Active', $active);
        return $this;
    }
}
