<?php

namespace Base\Cms\Paragraph;

use Base\Article\Translation\ArticleTranslationBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Join;
use Laminas\Db\Sql\Predicate\Expression;

/**
 * Class CmsSiteBeanFinder
 * @package Base\Cms\Site
 * @method DatabaseBeanLoader getLoader() : BeanLoaderInterface
 */
class CmsParagraphBeanFinder extends ArticleTranslationBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter, new CmsParagraphBeanFactory());
        $loader = $this->getLoader();
        if ($loader instanceof DatabaseBeanLoader) {
            $loader->addColumn('CmsParagraph_ID', 'CmsParagraph_ID', 'CmsParagraph', 'CmsParagraph_ID', true);
            $loader->addColumn('Article_ID', 'Article_ID', 'CmsParagraph', 'CmsParagraph_ID', false, null, ['Article', 'ArticleTranslation']);
        }
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
