<?php


namespace Base\Article\Translation;


use Base\Article\ArticleBeanFinder;
use Base\Cms\Paragraph\CmsParagraphBeanFinder;
use Base\Cms\Site\CmsSiteBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Join;
use Laminas\Db\Sql\Predicate\Expression;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanFinder\BeanLoaderInterface;

/**
 * Class ArticleTranslationBeanFinder
 * @package Base\Article\Translation
 * @method DatabaseBeanLoader getLoader() : BeanLoaderInterface
 */
class ArticleTranslationBeanFinder extends ArticleBeanFinder
{
    /**
     * ArticleTranslationBeanFinder constructor.
     * @param Adapter $adapter
     * @param BeanFactoryInterface|null $beanFactory
     */
    public function __construct(Adapter $adapter, BeanFactoryInterface $beanFactory = null)
    {
        parent::__construct($adapter, $beanFactory ?? new ArticleTranslationBeanFactory());
        $loader = $this->getLoader();
        if ($loader instanceof DatabaseBeanLoader) {
            $loader->addColumn('Article_ID', 'Article_ID', 'ArticleTranslation', 'Article_ID', true);
            $loader->addColumn('Locale_Code', 'Locale_Code', 'ArticleTranslation', 'Article_ID', true);
            $loader->addColumn('ArticleTranslation_Code', 'ArticleTranslation_Code', 'ArticleTranslation', 'Article_ID');
            $loader->addColumn('ArticleTranslation_Name', 'ArticleTranslation_Name', 'ArticleTranslation', 'Article_ID');
            $loader->addColumn('ArticleTranslation_Title', 'ArticleTranslation_Title', 'ArticleTranslation', 'Article_ID');
            $loader->addColumn('ArticleTranslation_Heading', 'ArticleTranslation_Heading', 'ArticleTranslation', 'Article_ID');
            $loader->addColumn('ArticleTranslation_SubHeading', 'ArticleTranslation_SubHeading', 'ArticleTranslation', 'Article_ID');
            $loader->addColumn('ArticleTranslation_Teaser', 'ArticleTranslation_Teaser', 'ArticleTranslation', 'Article_ID');
            $loader->addColumn('ArticleTranslation_Text', 'ArticleTranslation_Text', 'ArticleTranslation', 'Article_ID');
            $loader->addColumn('ArticleTranslation_Footer', 'ArticleTranslation_Footer', 'ArticleTranslation', 'Article_ID');
        }
    }

    /**
     * @param string $locale
     * @param bool $leftJoin
     * @return $this
     */
    public function setLocale_Code(string $locale, bool $leftJoin = true): self
    {
        if ($leftJoin) {
            $expression = new Expression("Article.Article_ID = ArticleTranslation.Article_ID AND ArticleTranslation.Locale_Code = ?", $locale);
            $this->getLoader()->addJoinInfo('ArticleTranslation', Join::JOIN_LEFT, $expression);
        } else {
            $this->getLoader()->filterValue('Locale_Code', $locale);
        }
        return $this;
    }


    /**
     * @param string $language
     * @param bool $limit
     * @return $this
     */
    public function setLanguage(string $language, bool $limit = true): self
    {
        $this->getLoader()->addLike("$language%", 'Locale_Code');
        if ($limit) {
            $this->getLoader()->limit(1, 0);
        }
        return $this;
    }


    /**
     * @param string $articleTranslation_Code
     * @return $this
     */
    public function setArticleTranslation_Code(string $articleTranslation_Code)
    {
        $this->getLoader()->filterValue('ArticleTranslation_Code', $articleTranslation_Code);
        return $this;
    }

}
