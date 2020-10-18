<?php


namespace Base\Cms\Site;


use Base\Article\Translation\ArticleTranslationBeanFinder;
use Base\Cms\SiteParagraph\CmsSiteParagraphBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Join;
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

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale_Code(string $locale): self
    {
        $expression = new Expression("Article.Article_ID = ArticleTranslation.Article_ID AND ArticleTranslation.Locale_Code = ?", $locale);
        $this->getLoader()->addJoinInfo('ArticleTranslation', Join::JOIN_LEFT, $expression);
        return $this;
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
