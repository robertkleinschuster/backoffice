<?php

namespace Pars\Base\Cms\PageParagraph;

use Niceshops\Bean\Finder\AbstractBeanFinder;
use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;

class CmsPageParagraphBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsPage_ID', 'CmsPage_ID', 'CmsPage_CmsParagraph', 'CmsPage_ID', true);
        $loader->addColumn('CmsParagraph_ID', 'CmsParagraph_ID', 'CmsPage_CmsParagraph', 'CmsParagraph_ID', true);
        $loader->addColumn('CmsPage_CmsParagraph_Order', 'CmsPage_CmsParagraph_Order', 'CmsPage_CmsParagraph', 'CmsParagraph_ID', true);
        $loader->addColumn('Article_ID', 'Article_ID', 'CmsParagraph', 'CmsParagraph_ID', false, null, ['Article']);
        $loader->addColumn('Article_Code', 'Article_Code', 'Article', 'Article_ID', false, null, [], 'CmsParagraph');
        $loader->addColumn('ArticleTranslation_Name', 'ArticleTranslation_Name', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('ArticleTranslation_Code', 'ArticleTranslation_Code', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('ArticleTranslation_Title', 'ArticleTranslation_Title', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('ArticleTranslation_Heading', 'ArticleTranslation_Heading', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('ArticleTranslation_SubHeading', 'ArticleTranslation_SubHeading', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('ArticleTranslation_Teaser', 'ArticleTranslation_Teaser', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('ArticleTranslation_Text', 'ArticleTranslation_Text', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('ArticleTranslation_Footer', 'ArticleTranslation_Footer', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('Locale_Code', 'Locale_Code', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addOrder('CmsPage_CmsParagraph_Order');
        parent::__construct($loader, new CmsPageParagraphBeanFactory());
    }


    /**
     * @param string $locale_Code
     * @return $this
     */
    public function setLocale_Code(string $locale_Code): self
    {
        $this->getBeanLoader()->filterValue('Locale_Code', $locale_Code);
        return $this;
    }

    /**
     * @param int $order
     */
    public function setCmsPage_CmsParagraph_Order(int $order): self
    {
        $this->getBeanLoader()->filterValue('CmsPage_CmsParagraph_Order', $order);
        return $this;
    }


    public function setCmsPage_ID(int $CmsPage_Id): self
    {
        $this->getBeanLoader()->filterValue('CmsPage_ID', $CmsPage_Id);
        return $this;
    }
}
