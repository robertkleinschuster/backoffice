<?php


namespace Base\Cms\Menu;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsMenuBeanProcessor extends AbstractBeanProcessor
{
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsMenu_ID', 'CmsMenu_ID', 'CmsMenu', 'CmsMenu_ID', true);
        $saver->addColumn('CmsMenu_ID_Parent', 'CmsMenu_ID_Parent', 'CmsMenu', 'CmsMenu_ID');
        $saver->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsMenu', 'CmsMenu_ID');
        $saver->addColumn('Translation_Code_Title', 'Translation_Code_Title', 'CmsMenu', 'CmsMenu_ID');
        parent::__construct($saver);
    }

}
