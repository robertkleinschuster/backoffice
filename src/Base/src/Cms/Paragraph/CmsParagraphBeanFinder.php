<?php

namespace Pars\Base\Cms\Paragraph;

use Pars\Base\Article\Translation\ArticleTranslationBeanFinder;
use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;


/**
 * Class CmsSiteBeanFinder
 * @package Pars\Base\Cms\Site
 * @method DatabaseBeanLoader getBeanLoader() : BeanLoaderInterface
 */
class CmsParagraphBeanFinder extends ArticleTranslationBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter, new CmsParagraphBeanFactory());
        $loader = $this->getBeanLoader();
        if ($loader instanceof DatabaseBeanLoader) {
            $loader->addColumn('CmsParagraph_ID', 'CmsParagraph_ID', 'CmsParagraph', 'CmsParagraph_ID', true);
            $loader->addColumn('CmsParagraphType_Code', 'CmsParagraphType_Code', 'CmsParagraph', 'CmsParagraph_ID');
            $loader->addColumn('CmsParagraphState_Code', 'CmsParagraphState_Code', 'CmsParagraph', 'CmsParagraph_ID');
            $loader->addColumn('Article_ID', 'Article_ID', 'CmsParagraph', 'CmsParagraph_ID', false, null, ['Article', 'ArticleTranslation']);
        }
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
