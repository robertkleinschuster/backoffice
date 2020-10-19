<?php


namespace Base\Cms\Menu;


use Base\Article\Translation\ArticleTranslationBeanFinder;
use Laminas\Db\Adapter\Adapter;



class CmsMenuBeanFinder extends ArticleTranslationBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter, new CmsMenuBeanFactory());
        $loader = $this->getLoader();
        $loader->resetDbInfo();
        $loader->addColumn('CmsMenu_ID', 'CmsMenu_ID', 'CmsMenu', 'CmsMenu_ID', true);
        $loader->addColumn('CmsMenu_ID_Parent', 'CmsMenu_ID_Parent', 'CmsMenu', 'CmsMenu_ID');
        $loader->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsMenu', 'CmsMenu_ID', false, null, ['CmsSite']);
        $loader->addColumn('CmsMenu_Order', 'CmsMenu_Order', 'CmsMenu', 'CmsMenu_ID');
        $loader->addColumn('Article_ID', 'Article_ID', 'CmsSite', 'CmsSite_ID', false, null, ['Article']);
        $loader->addColumn('Article_Code', 'Article_Code', 'Article', 'Article_ID', false, null, [], 'CmsSite');
        $loader->addColumn('ArticleTranslation_Name', 'ArticleTranslation_Name', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('ArticleTranslation_Code', 'ArticleTranslation_Code', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('Locale_Code', 'Locale_Code', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
    }
}
