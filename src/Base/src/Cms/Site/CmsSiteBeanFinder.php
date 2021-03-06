<?php


namespace Base\Cms\Site;


use Base\Article\Translation\ArticleTranslationBeanFinder;
use Base\Cms\SiteParagraph\CmsSiteParagraphBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Join;
use Laminas\Db\Sql\Predicate\Like;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

/**
 * Class CmsSiteBeanFinder
 * @package Base\Cms\Site
 * @method DatabaseBeanLoader getLoader() : BeanLoaderInterface
 */
class CmsSiteBeanFinder extends ArticleTranslationBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter, new CmsSiteBeanFactory());
        $loader = $this->getLoader();
        if ($loader instanceof  DatabaseBeanLoader) {
            $loader->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsSite', 'CmsSite_ID', true);
            $loader->addColumn('Article_ID', 'Article_ID', 'CmsSite', 'CmsSite_ID', false, null, ['Article', 'ArticleTranslation']);
        }
        $this->linkBeanFinder(new CmsSiteParagraphBeanFinder($adapter), 'CmsParagraph_BeanList', 'CmsSite_ID', 'CmsSite_ID');
    }


    public function setLocale_Code(string $locale, bool $leftJoin = true): ArticleTranslationBeanFinder
    {
        foreach ($this->getBeanFinderLinkList() as $finderLink) {
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
        $this->getLoader()->filterValue('ArticleTranslation_Code', $code);
        return $this;
    }

}
