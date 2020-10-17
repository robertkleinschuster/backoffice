<?php


namespace Base\Cms\SiteParagraph;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsSiteParagraphBeanProcessor extends AbstractBeanProcessor
{
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsSite_CmsParagraph', 'CmsSite_ID', true);
        $saver->addColumn('CmsParagraphn_ID', 'CmsParagraphn_ID', 'CmsSite_CmsParagraph', 'CmsParagraphn_ID', true);
        parent::__construct($saver);
    }
}
