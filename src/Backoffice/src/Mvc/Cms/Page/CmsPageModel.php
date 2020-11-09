<?php

namespace Pars\Backoffice\Mvc\Cms\Page;

use Pars\Backoffice\Mvc\Article\ArticleModel;
use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Cms\Page\State\CmsPageStateBeanFinder;
use Pars\Base\Cms\Page\Type\CmsPageTypeBeanFinder;
use Pars\Base\Cms\Page\CmsPageBeanFinder;
use Pars\Base\Cms\Page\CmsPageBeanProcessor;
use Pars\Base\Cms\PageParagraph\CmsPageParagraphBeanFinder;

/**
 * Class CmsPageModel
 * @package Pars\Backoffice\Mvc\Cms\Page
 */
class CmsPageModel extends ArticleModel
{

    /**
     * @inheritDoc
     */
    public function initialize()
    {
        $this->setBeanFinder(new CmsPageBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsPageBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }

    public function getCmsPageType_Options(): array
    {
        $options = [];
        $finder = new CmsPageTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsPageType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsPageType_Code')] = $this->translate("CmsPagetype.code." . $bean->getData('CmsPageType_Code'));
        }
        return $options;
    }

    public function getCmsPageState_Options(): array
    {
        $options = [];
        $finder = new CmsPageStateBeanFinder($this->getDbAdpater());
        $finder->setCmsPageState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsPageState_Code')] = $this->translate("CmsPagestate.code." . $bean->getData('CmsPageState_Code'));
        }
        return $options;
    }

    public function getParagraph_List(array $viewIdMap)
    {
        $finder = new CmsPageParagraphBeanFinder($this->getDbAdpater());
        $finder->getBeanLoader()->initByIdMap($viewIdMap);
        return $finder->getBeanListDecorator();
    }
}
