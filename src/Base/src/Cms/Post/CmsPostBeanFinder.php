<?php

namespace Base\Cms\Post;

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
class CmsPostBeanFinder extends ArticleTranslationBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter, new CmsPostBeanFactory());
        $loader = $this->getLoader();
        if ($loader instanceof DatabaseBeanLoader) {
            $loader->addColumn('CmsPost_ID', 'CmsPost_ID', 'CmsPost', 'CmsPost_ID', true);
            $loader->addColumn('CmsPostType_Code', 'CmsPostType_Code', 'CmsPost', 'CmsPost_ID');
            $loader->addColumn('CmsPostState_Code', 'CmsPostState_Code', 'CmsPost', 'CmsPost_ID');
            $loader->addColumn('Article_ID', 'Article_ID', 'CmsPost', 'CmsPost_ID', false, null, ['Article', 'ArticleTranslation']);
        }
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
