<?php


namespace Base\Article\State;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;
use NiceshopsDev\Bean\BeanFinder\BeanLoaderInterface;

class ArticleStateBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('ArticleState_Code', 'ArticleState_Code', 'ArticleState', 'ArticleState_Code', true);
        $loader->addColumn('ArticleState_Active', 'ArticleState_Active', 'ArticleState', 'ArticleState_Code');
        parent::__construct($loader, new ArticleStateBeanFactory());
    }

    public function setArticleState_Active(bool $active): self
    {
        $this->getLoader()->filterValue('ArticleState_Active', $active);
        return $this;
    }

}
