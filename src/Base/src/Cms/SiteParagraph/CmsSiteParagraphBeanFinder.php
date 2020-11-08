<?php

namespace Pars\Base\Cms\SiteParagraph;

use Niceshops\Bean\Finder\AbstractBeanFinder;
use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;

class CmsSiteParagraphBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsSite_CmsParagraph', 'CmsSite_ID', true);
        $loader->addColumn('CmsParagraph_ID', 'CmsParagraph_ID', 'CmsSite_CmsParagraph', 'CmsParagraph_ID', true);
        $loader->addColumn('CmsSite_CmsParagraph_Order', 'CmsSite_CmsParagraph_Order', 'CmsSite_CmsParagraph', 'CmsParagraph_ID', true);
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
        $loader->addOrder('CmsSite_CmsParagraph_Order');
        parent::__construct($loader, new CmsSiteParagraphBeanFactory());
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
    public function setCmsSite_CmsParagraph_Order(int $order): self
    {
        $this->getBeanLoader()->filterValue('CmsSite_CmsParagraph_Order', $order);
        return $this;
    }


    public function setCmsSite_ID(int $cmsSite_Id): self
    {
        $this->getBeanLoader()->filterValue('CmsSite_ID', $cmsSite_Id);
        return $this;
    }
}
