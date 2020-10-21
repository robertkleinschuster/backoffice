<?php


namespace Backoffice\Mvc\Cms\Paragraph;


use Backoffice\Mvc\Base\BaseModel;
use Base\Cms\Paragraph\State\CmsParagraphStateBeanFinder;
use Base\Cms\Paragraph\Type\CmsParagraphTypeBeanFinder;
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


    public function getCmsParagraphType_Options(): array
    {
        $options = [];
        $finder = new CmsParagraphTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsParagraphType_Active(true);
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('CmsParagraphType_Code')] = $bean->getData('CmsParagraphType_Code');
        }
        return $options;
    }

    public function getCmsParagraphState_Options(): array
    {
        $options = [];
        $finder = new CmsParagraphStateBeanFinder($this->getDbAdpater());
        $finder->setCmsParagraphState_Active(true);
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('CmsParagraphState_Code')] = $bean->getData('CmsParagraphState_Code');
        }
        return $options;
    }

}
