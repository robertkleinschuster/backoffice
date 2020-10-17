<?php


namespace Backoffice\Mvc\Cms\Site;


use Base\Article\ArticleBeanProcessor;
use Base\Article\State\ArticleStateBeanFinder;
use Base\Article\Translation\ArticleTranslationBeanProcessor;
use Base\Article\Type\ArticleTypeBeanFinder;
use Base\Cms\Site\CmsSiteBeanFinder;
use Base\Cms\Site\CmsSiteBeanProcessor;
use Mvc\Helper\ValidationHelperAwareInterface;
use NiceshopsDev\Bean\BeanInterface;

class CmsSiteModel extends \Backoffice\Mvc\Base\BaseModel
{

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->setFinder(new CmsSiteBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new CmsSiteBeanProcessor($this->getDbAdpater()));
        $this->getFinder()->setLocale_Code($this->getTranslator()->getLocale());

    }

    public function getArticleType_Options(): array
    {
        $options = [];
        $finder = new ArticleTypeBeanFinder($this->getDbAdpater());
        $finder->setArticleType_Active(true);
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('ArticleType_Code')] = $bean->getData('ArticleType_Code');
        }
        return $options;
    }

    public function getArticleState_Options(): array
    {
        $options = [];
        $finder = new ArticleStateBeanFinder($this->getDbAdpater());
        $finder->setArticleState_Active(true);
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('ArticleState_Code')] = $bean->getData('ArticleState_Code');
        }
        return $options;
    }

}
