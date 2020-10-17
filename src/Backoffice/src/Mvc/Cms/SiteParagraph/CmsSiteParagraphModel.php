<?php


namespace Backoffice\Mvc\Cms\SiteParagraph;


use Backoffice\Mvc\Base\BaseModel;
use Base\Cms\Paragraph\CmsParagraphBeanFinder;
use Base\Cms\SiteParagraph\CmsSiteParagraphBeanFinder;
use Base\Cms\SiteParagraph\CmsSiteParagraphBeanProcessor;

class CmsSiteParagraphModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new CmsSiteParagraphBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new CmsSiteParagraphBeanProcessor($this->getDbAdpater()));
    }

    /**
     * @return array
     * @throws \NiceshopsDev\Bean\BeanException
     */
    public function getParagraph_Options()
    {
        $beanList = $this->getFinder()->getBeanGenerator();
        $existing = $beanList->getData('CmsParagraph_ID');
        $options = [];
        $finder = new CmsParagraphBeanFinder($this->getDbAdpater());
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            if (!in_array($bean->getData('CmsParagraph_ID'), $existing)) {
                $options[$bean->getData('CmsParagraph_ID')] = $bean->getData('ArticleTranslation_Name');
            }
        }
        return $options;
    }
}
