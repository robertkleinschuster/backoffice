<?php


namespace Base\Cms\Paragraph;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;
use NiceshopsDev\Bean\BeanFinder\BeanLoaderInterface;

class CmsParagraphBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsParagraph_ID', 'CmsParagraph_ID', 'CmsParagraph', 'CmsParagraph_ID', true);
        $loader->addColumn('Article_ID', 'Article_ID', 'CmsParagraph', 'CmsParagraph_ID');
        parent::__construct($loader, new CmsParagraphBeanFactory());
    }

}
