<?php


namespace Base\Cms\Paragraph;


use Base\Database\DatabaseBeanLoader;
use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsParagraphBeanProcessor extends AbstractBeanProcessor
{
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsParagraph_ID', 'CmsParagraph_ID', 'CmsParagraph', 'CmsParagraph_ID', true);
        $saver->addColumn('Article_ID', 'Article_ID', 'CmsParagraph', 'CmsParagraph_ID');
        parent::__construct($saver);
    }

}
