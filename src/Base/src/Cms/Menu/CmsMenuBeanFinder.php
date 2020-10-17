<?php


namespace Base\Cms\Menu;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Join;
use Laminas\Db\Sql\Predicate\Expression;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class CmsMenuBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsMenu_ID', 'CmsMenu_ID', 'CmsMenu', 'CmsMenu_ID', true);
        $loader->addColumn('CmsMenu_ID_Parent', 'CmsMenu_ID_Parent', 'CmsMenu', 'CmsMenu_ID');
        $loader->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsMenu', 'CmsMenu_ID', false, null, ['CmsSite']);
        $loader->addColumn('Article_ID', 'Article_ID', 'CmsSite', 'CmsSite_ID', false, null, ['Article']);
        $loader->addColumn('Article_Code', 'Article_Code', 'Article', 'Article_ID', false, null, [], 'CmsSite');
        $loader->addColumn('ArticleTranslation_Name', 'ArticleTranslation_Name', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('ArticleTranslation_Code', 'ArticleTranslation_Code', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        $loader->addColumn('Locale_Code', 'Locale_Code', 'ArticleTranslation', 'Article_ID', false, null, [], 'Article');
        parent::__construct($loader, new CmsMenuBeanFactory());
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

}
