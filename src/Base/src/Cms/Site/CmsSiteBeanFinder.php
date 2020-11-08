<?php

namespace Pars\Base\Cms\Site;

use Pars\Base\Article\Translation\ArticleTranslationBeanFinder;
use Pars\Base\Cms\SiteParagraph\CmsSiteParagraphBeanFinder;
use Laminas\Db\Adapter\Adapter;
use Pars\Base\Database\DatabaseBeanLoader;

/**
 * Class CmsSiteBeanFinder
 * @package Pars\Base\Cms\Site
 * @method DatabaseBeanLoader getBeanLoader() : BeanLoaderInterface
 */
class CmsSiteBeanFinder extends ArticleTranslationBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter, new CmsSiteBeanFactory());
        $loader = $this->getBeanLoader();
        if ($loader instanceof  DatabaseBeanLoader) {
            $loader->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsSite', 'CmsSite_ID', true);
            $loader->addColumn('CmsSiteType_Code', 'CmsSiteType_Code', 'CmsSite', 'CmsSite_ID');
            $loader->addColumn('CmsSiteType_Template', 'CmsSiteType_Template', 'CmsSiteType', 'CmsSiteType_Code');
            $loader->addColumn('CmsSiteState_Code', 'CmsSiteState_Code', 'CmsSite', 'CmsSite_ID');
            $loader->addColumn('Article_ID', 'Article_ID', 'CmsSite', 'CmsSite_ID', false, null, ['Article', 'ArticleTranslation']);
        }
        $this->addLinkedFinder(new CmsSiteParagraphBeanFinder($adapter), 'CmsParagraph_BeanList', 'CmsSite_ID', 'CmsSite_ID');
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
