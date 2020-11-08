<?php

namespace Pars\Backoffice\Mvc\Cms\Site;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Cms\Site\State\CmsSiteStateBeanFinder;
use Pars\Base\Cms\Site\Type\CmsSiteTypeBeanFinder;
use Pars\Base\Cms\Site\CmsSiteBeanFinder;
use Pars\Base\Cms\Site\CmsSiteBeanProcessor;
use Pars\Base\Cms\SiteParagraph\CmsSiteParagraphBeanFinder;

class CmsSiteModel extends CrudModel
{

    /**
     * @inheritDoc
     */
    public function initialize()
    {
        $this->setBeanFinder(new CmsSiteBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsSiteBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }

    public function getCmsSiteType_Options(): array
    {
        $options = [];
        $finder = new CmsSiteTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsSiteType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsSiteType_Code')] = $this->translate("cmssitetype.code." . $bean->getData('CmsSiteType_Code'));
        }
        return $options;
    }

    public function getCmsSiteState_Options(): array
    {
        $options = [];
        $finder = new CmsSiteStateBeanFinder($this->getDbAdpater());
        $finder->setCmsSiteState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsSiteState_Code')] = $this->translate("cmssitestate.code." . $bean->getData('CmsSiteState_Code'));
        }
        return $options;
    }

    public function getParagraph_List(array $viewIdMap)
    {
        $finder = new CmsSiteParagraphBeanFinder($this->getDbAdpater());
        $finder->getBeanLoader()->initByIdMap($viewIdMap);
        return $finder->getBeanListDecorator();
    }
}
