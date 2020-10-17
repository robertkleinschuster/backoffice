<?php


namespace Base\Cms\SiteParagraph;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;
use NiceshopsDev\Bean\BeanFinder\BeanLoaderInterface;

class CmsSiteParagraphBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsSite_CmsParagraph', 'CmsSite_ID', true);
        $loader->addColumn('CmsParagraphn_ID', 'CmsParagraphn_ID', 'CmsSite_CmsParagraph', 'CmsParagraphn_ID', true);
        parent::__construct($loader, new CmsSiteParagraphBeanFactory());
    }

}
