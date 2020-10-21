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
        $loader->addColumn('CmsSite_ID_Parent', 'CmsSite_ID_Parent', 'CmsMenu', 'CmsMenu_ID');
        $loader->addColumn('CmsMenu_Order', 'CmsMenu_Order', 'CmsMenu', 'CmsMenu_ID');
        $loader->addColumn('CmsMenuType_Code', 'CmsMenuType_Code', 'CmsMenu', 'CmsMenu_ID');
        $loader->addColumn('CmsMenuState_Code', 'CmsMenuState_Code', 'CmsMenu', 'CmsMenu_ID');
        $loader->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsMenu', 'CmsMenu_ID', false, null, ['CmsSite']);
        $loader->addColumn('Article_ID', 'Article_ID', 'CmsSite', 'CmsSite_ID', false, null, ['Article']);
        $loader->addColumn('Article_Code', 'Article_Code', 'Article', 'Article_ID', false, null, [], 'CmsSite');
        $loader->addColumn('ArticleTranslation_Name', 'ArticleTranslation_Name', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('ArticleTranslation_Code', 'ArticleTranslation_Code', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('Locale_Code', 'Locale_Code', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addOrder('CmsMenu_Order');
    }

    public function setCmsMenu_Order(int $order): self
    {
        $this->getLoader()->filterValue('CmsMenu_Order', $order);
        return $this;
    }

    public function setCmsMenu_ID_Parent(int $parent): self
    {
        $this->getLoader()->filterValue('CmsMenu_ID_Parent', $parent);
        return $this;
    }

    public function setCmsSite_ID_Parent(int $parent): self
    {
        $this->getLoader()->filterValue('CmsSite_ID_Parent', $parent);
        return $this;
    }

    public function setCmsMenuType_Code(string $type): self
    {
        $this->getLoader()->filterValue('CmsMenuType_Code', $type);
        return $this;
    }

    public function setCmsMenuState_Code(string $type): self
    {
        $this->getLoader()->filterValue('CmsMenuState_Code', $type);
        return $this;
    }

}
