<?php

namespace Pars\Base\Article\Translation;

use Pars\Base\Article\ArticleBeanFinder;
use Pars\Base\Database\DatabaseBeanLoader;
use Pars\Base\File\FileBeanFinder;
use Pars\Base\Localization\Locale\LocaleBeanFinder;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Join;
use Laminas\Db\Sql\Predicate\Expression;
use Niceshops\Bean\Factory\BeanFactoryInterface;

/**
 * Class ArticleTranslationBeanFinder
 * @package Pars\Base\Article\Translation
 * @method DatabaseBeanLoader getBeanLoader() : BeanLoaderInterface
 */
class ArticleTranslationBeanFinder extends ArticleBeanFinder
{

    private $adapter;

    /**
     * ArticleTranslationBeanFinder constructor.
     * @param Adapter $adapter
     * @param BeanFactoryInterface|null $beanFactory
     */
    public function __construct(Adapter $adapter, BeanFactoryInterface $beanFactory = null)
    {
        $this->adapter = $adapter;
        parent::__construct($adapter, $beanFactory ?? new ArticleTranslationBeanFactory());
        $loader = $this->getBeanLoader();
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
            $loader->addColumn('File_ID', 'File_ID', 'ArticleTranslation', 'Article_ID');
            $this->addLinkedFinder(new FileBeanFinder($adapter), 'File_BeanList', 'File_ID', 'File_ID');
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
            $this->getBeanLoader()->addJoinInfo('ArticleTranslation', Join::JOIN_LEFT, $expression);
        } else {
            $this->getBeanLoader()->filterValue('Locale_Code', $locale);
        }
        return $this;
    }

    /**
     * @param string $articleTranslation_Code
     * @return $this
     */
    public function setArticleTranslation_Code(string $articleTranslation_Code)
    {
        $this->getBeanLoader()->filterValue('ArticleTranslation_Code', $articleTranslation_Code);
        return $this;
    }

    /**
     * @param string $localeCode
     * @param string $fallback
     */
    public function findByLocaleWithFallback(string $localeCode, string $fallback)
    {
        $this->setLocale_Code($localeCode, false);
        $count = $this->count();
        if ($count == 0) {
            // Find similar locales
            $language = \Locale::getPrimaryLanguage($localeCode);
            $localeFinder = new LocaleBeanFinder($this->adapter);
            $localeFinder->setLocale_Active(true);
            $localeFinder->setLanguage($language);
            $generator = $localeFinder->getBeanListDecorator();
            foreach ($generator as $localeBean) {
                $this->setLocale_Code($localeBean->getData('Locale_Code'), false);
                $count = $this->count();
                if ($count > 0) {
                    return $count;
                }
            }
            $this->setLocale_Code($fallback, false);
            return $this->count();
        } else {
            return $count;
        }
    }
}
