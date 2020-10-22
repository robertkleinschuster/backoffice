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
        $this->getFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }

    /**
     * @return array
     * @throws \NiceshopsDev\Bean\BeanException
     */
    public function getParagraph_Options()
    {
        $options = [];
        $finder = new CmsParagraphBeanFinder($this->getDbAdpater());
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('CmsParagraph_ID')] = $bean->getData('ArticleTranslation_Name');
        }
        return $options;
    }

    public function orderUp()
    {
        $bean = $this->getFinder()->getBean();
        if ($bean->hasData('CmsSite_CmsParagraph_Order') && $bean->getData('CmsSite_CmsParagraph_Order') > 1) {
            $finder = new CmsSiteParagraphBeanFinder($this->getDbAdpater());
            $finder->setCmsSite_ID($bean->getData('CmsSite_ID'));
            $finder->setCmsSite_CmsParagraph_Order($bean->getData('CmsSite_CmsParagraph_Order') - 1);
            $finder->limit(1,0);
            if ($finder->find() == 1) {
                $previuousBean = $finder->getBean();
                $bean->setData('CmsSite_CmsParagraph_Order', $previuousBean->getData('CmsSite_CmsParagraph_Order'));
                $previuousBean->setData('CmsSite_CmsParagraph_Order', $previuousBean->getData('CmsSite_CmsParagraph_Order') + 1 );
                $this->saveBeanWithProcessor($previuousBean);
            } else {
                $bean->setData('CmsSite_CmsParagraph_Order', 1);
            }
            $this->saveBeanWithProcessor($bean);

        }
    }

    public function orderDown()
    {
        $bean = $this->getFinder()->getBean();
        $finder = new CmsSiteParagraphBeanFinder($this->getDbAdpater());
        $finder->setCmsSite_ID($bean->getData('CmsSite_ID'));
        $finder->setLocale_Code($this->getTranslator()->getLocale());
        if ($bean->hasData('CmsSite_CmsParagraph_Order') && $bean->getData('CmsSite_CmsParagraph_Order') < $finder->find()) {
            $finder = new CmsSiteParagraphBeanFinder($this->getDbAdpater());
            $finder->setCmsSite_ID($bean->getData('CmsSite_ID'));
            $finder->setLocale_Code($this->getTranslator()->getLocale());
            $finder->setCmsSite_CmsParagraph_Order($bean->getData('CmsSite_CmsParagraph_Order') + 1);
            $finder->limit(1,0);
            if ($finder->find()== 1) {
                $nextBean = $finder->getBean();
                $bean->setData('CmsSite_CmsParagraph_Order', $nextBean->getData('CmsSite_CmsParagraph_Order'));
                $nextBean->setData('CmsSite_CmsParagraph_Order', $nextBean->getData('CmsSite_CmsParagraph_Order') - 1 );
                $this->saveBeanWithProcessor($nextBean);
            } else {
                $bean->setData('CmsSite_CmsParagraph_Order', 1);
            }
            $this->saveBeanWithProcessor($bean);
        }
    }
}
