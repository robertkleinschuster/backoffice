<?php

namespace Pars\Backoffice\Mvc\Cms\Menu;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Cms\Menu\CmsMenuBeanFinder;
use Pars\Base\Cms\Menu\CmsMenuBeanProcessor;
use Pars\Base\Cms\Menu\State\CmsMenuStateBeanFinder;
use Pars\Base\Cms\Menu\Type\CmsMenuTypeBeanFinder;
use Pars\Base\Cms\Site\CmsSiteBeanFinder;

/**
 * Class CmsMenuModel
 * @package Pars\Backoffice\Mvc\Cms\Menu
 * @method CmsMenuBeanFinder getBeanFinder() : BeanFinderInterface
 */
class CmsMenuModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsMenuBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsMenuBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }


    public function getCmsSite_Options(): array
    {
        $options = [];
        $finder = new CmsSiteBeanFinder($this->getDbAdpater());
        $finder->setLocale_Code($this->getTranslator()->getLocale());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsSite_ID')] = $bean->getData('ArticleTranslation_Name');
        }
        return $options;
    }

    public function getCmsMenuState_Options(): array
    {
        $options = [];
        $finder = new CmsMenuStateBeanFinder($this->getDbAdpater());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsMenuState_Code')] = $bean->getData('CmsMenuState_Code');
        }
        return $options;
    }

    public function getCmsMenuType_Options(): array
    {
        $options = [];
        $finder = new CmsMenuTypeBeanFinder($this->getDbAdpater());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsMenuType_Code')] = $bean->getData('CmsMenuType_Code');
        }
        return $options;
    }
}
