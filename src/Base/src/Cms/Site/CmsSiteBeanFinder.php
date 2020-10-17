<?php


namespace Base\Cms\Site;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class CmsSiteBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsSite', 'CmsSite_ID', true);
        $loader->addColumn('Article_ID', 'Article_ID', 'CmsSite', 'CmsSite_ID');
        parent::__construct($loader, new CmsSiteBeanFactory());
    }
}
