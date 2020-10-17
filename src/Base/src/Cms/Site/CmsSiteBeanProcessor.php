<?php


namespace Base\Cms\Site;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsSiteBeanProcessor extends AbstractBeanProcessor
{

    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsSite', 'CmsSite_ID', true);
        $saver->addColumn('Article_ID', 'Article_ID', 'CmsParagraph', 'CmsSite_ID');
        parent::__construct($saver);
    }

}
