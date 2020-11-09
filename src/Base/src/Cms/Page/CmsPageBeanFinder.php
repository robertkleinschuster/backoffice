<?php

namespace Pars\Base\Cms\Page;

use Pars\Base\Article\Translation\ArticleTranslationBeanFinder;
use Pars\Base\Cms\PageParagraph\CmsPageParagraphBeanFinder;
use Laminas\Db\Adapter\Adapter;
use Pars\Base\Database\DatabaseBeanLoader;

/**
 * Class CmsPageBeanFinder
 * @package Pars\Base\Cms\Page
 * @method DatabaseBeanLoader getBeanLoader() : BeanLoaderInterface
 */
class CmsPageBeanFinder extends ArticleTranslationBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter, new CmsPageBeanFactory());
        $loader = $this->getBeanLoader();
        if ($loader instanceof  DatabaseBeanLoader) {
            $loader->addColumn('CmsPage_ID', 'CmsPage_ID', 'CmsPage', 'CmsPage_ID', true);
            $loader->addColumn('CmsPageType_Code', 'CmsPageType_Code', 'CmsPage', 'CmsPage_ID');
            $loader->addColumn('CmsPageType_Template', 'CmsPageType_Template', 'CmsPageType', 'CmsPageType_Code');
            $loader->addColumn('CmsPageState_Code', 'CmsPageState_Code', 'CmsPage', 'CmsPage_ID');
            $loader->addColumn('Article_ID', 'Article_ID', 'CmsPage', 'CmsPage_ID', false, null, ['Article', 'ArticleTranslation']);
        }
        $this->addLinkedFinder(new CmsPageParagraphBeanFinder($adapter), 'CmsParagraph_BeanList', 'CmsPage_ID', 'CmsPage_ID');
    }


    public function setLocale_Code(string $locale, bool $leftJoin = true): ArticleTranslationBeanFinder
    {
        foreach ($this->getLinkedFinderList() as $finderLink) {
            if (method_exists($finderLink->getBeanFinder(), 'setLocale_Code')) {
                $finderLink->getBeanFinder()->setLocale_Code($locale, $leftJoin);
            }
        }
        return parent::setLocale_Code($locale, $leftJoin);
    }


    /**
     * @param string $code
     * @return $this
     * @throws \Exception
     */
    public function setArticleTranslation_Code(string $code): self
    {
        $this->getBeanLoader()->filterValue('ArticleTranslation_Code', $code);
        return $this;
    }
}
