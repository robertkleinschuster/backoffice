<?php

namespace Pars\Backoffice\Mvc\Cms\Paragraph;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Cms\Paragraph\State\CmsParagraphStateBeanFinder;
use Pars\Base\Cms\Paragraph\Type\CmsParagraphTypeBeanFinder;
use Pars\Base\Cms\Paragraph\CmsParagraphBeanFinder;
use Pars\Base\Cms\Paragraph\CmsParagraphBeanProcessor;

class CmsParagraphModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsParagraphBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsParagraphBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }


    public function getCmsParagraphType_Options(): array
    {
        $options = [];
        $finder = new CmsParagraphTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsParagraphType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsParagraphType_Code')] = $this->translate('cmsparagraphtype.code.' . $bean->getData('CmsParagraphType_Code'));
        }
        return $options;
    }

    public function getCmsParagraphState_Options(): array
    {
        $options = [];
        $finder = new CmsParagraphStateBeanFinder($this->getDbAdpater());
        $finder->setCmsParagraphState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsParagraphState_Code')] = $this->translate('cmsparagraphstate.code.' . $bean->getData('CmsParagraphState_Code'));
        }
        return $options;
    }
}
