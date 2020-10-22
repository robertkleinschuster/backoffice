<?php


namespace Backoffice\Mvc\Cms\Site;


use Base\Cms\Site\State\CmsSiteStateBeanFinder;
use Base\Cms\Site\Type\CmsSiteTypeBeanFinder;
use Base\Cms\Site\CmsSiteBeanFinder;
use Base\Cms\Site\CmsSiteBeanProcessor;
use Base\Cms\SiteParagraph\CmsSiteParagraphBeanFinder;

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

    public function getCmsSiteType_Options(): array
    {
        $options = [];
        $finder = new CmsSiteTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsSiteType_Active(true);
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('CmsSiteType_Code')] = $this->translate("cmssitetype.code." . $bean->getData('CmsSiteType_Code'));
        }
        return $options;
    }

    public function getCmsSiteState_Options(): array
    {
        $options = [];
        $finder = new CmsSiteStateBeanFinder($this->getDbAdpater());
        $finder->setCmsSiteState_Active(true);
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('CmsSiteState_Code')] = $this->translate("cmssitestate.code." . $bean->getData('CmsSiteState_Code'));
        }
        return $options;
    }

    public function getParagraph_List(array $viewIdMap)
    {
        $finder = new CmsSiteParagraphBeanFinder($this->getDbAdpater());
        $finder->getLoader()->initByIdMap($viewIdMap);
        $finder->find();
        return $finder->getBeanGenerator();
    }

}
