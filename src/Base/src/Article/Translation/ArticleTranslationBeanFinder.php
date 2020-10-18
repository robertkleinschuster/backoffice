<?php


namespace Base\Article\Translation;


use Base\Article\ArticleBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;

class ArticleTranslationBeanFinder extends ArticleBeanFinder
{
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
     * @param string $locale_Code
     * @return $this
     */
    public function setLocale_Code(string $locale_Code)
    {
        $this->getLoader()->filterValue('Locale_Code', $locale_Code);
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
