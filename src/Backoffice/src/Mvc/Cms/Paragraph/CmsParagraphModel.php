<?php


namespace Backoffice\Mvc\Cms\Paragraph;


use Backoffice\Mvc\Base\BaseModel;
use Base\Article\State\ArticleStateBeanFinder;
use Base\Article\Type\ArticleTypeBeanFinder;
use Base\Cms\Paragraph\CmsParagraphBeanFinder;
use Base\Cms\Paragraph\CmsParagraphBeanProcessor;

class CmsParagraphModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new CmsParagraphBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new CmsParagraphBeanProcessor($this->getDbAdpater()));
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
