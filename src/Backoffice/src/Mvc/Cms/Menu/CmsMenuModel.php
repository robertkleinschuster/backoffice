<?php


namespace Backoffice\Mvc\Cms\Menu;


use Backoffice\Mvc\Base\BaseModel;
use Base\Cms\Menu\CmsMenuBeanFinder;
use Base\Cms\Menu\CmsMenuBeanProcessor;
use Base\Cms\Menu\State\CmsMenuStateBeanFinder;
use Base\Cms\Menu\Type\CmsMenuTypeBeanFinder;
use Base\Cms\Site\CmsSiteBeanFinder;

/**
 * Class CmsMenuModel
 * @package Backoffice\Mvc\Cms\Menu
 * @method CmsMenuBeanFinder getFinder() : BeanFinderInterface
 */
class CmsMenuModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new CmsMenuBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new CmsMenuBeanProcessor($this->getDbAdpater()));
        $this->getFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }

    public function getCmsSite_Options(): array
    {
        $options = [];
        $finder = new CmsSiteBeanFinder($this->getDbAdpater());
        $finder->setLocale_Code($this->getTranslator()->getLocale());
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('CmsSite_ID')] = $bean->getData('ArticleTranslation_Name');
        }
        return $options;
    }

    public function getCmsMenuState_Options(): array
    {
        $options = [];
        $finder = new CmsMenuStateBeanFinder($this->getDbAdpater());
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('CmsMenuState_Code')] = $bean->getData('CmsMenuState_Code');
        }
        return $options;
    }

    public function getCmsMenuType_Options(): array
    {
        $options = [];
        $finder = new CmsMenuTypeBeanFinder($this->getDbAdpater());
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('CmsMenuType_Code')] = $bean->getData('CmsMenuType_Code');
        }
        return $options;
    }

    public function orderUp()
    {
        $bean = $this->getFinder()->getBean();
        if ($bean->hasData('CmsMenu_Order') && $bean->getData('CmsMenu_Order') > 1) {
            $finder = new CmsMenuBeanFinder($this->getDbAdpater());
            $finder->setCmsMenu_Order($bean->getData('CmsMenu_Order') - 1);
            $finder->limit(1,0);
            if ($finder->find() == 1) {
                $previuousBean = $finder->getBean();
                $bean->setData('CmsMenu_Order', $previuousBean->getData('CmsMenu_Order'));
                $previuousBean->setData('CmsMenu_Order', $previuousBean->getData('CmsMenu_Order') + 1 );
                $this->saveBeanWithProcessor($previuousBean);
            } else {
                $bean->setData('CmsMenu_Order', 1);
            }
            $this->saveBeanWithProcessor($bean);
        }
    }

    public function orderDown()
    {
        $bean = $this->getFinder()->getBean();
        $finder = new CmsMenuBeanFinder($this->getDbAdpater());
        if ($bean->hasData('CmsMenu_Order') && $bean->getData('CmsMenu_Order') < $finder->count()) {
            $finder = new CmsMenuBeanFinder($this->getDbAdpater());
            $finder->setCmsMenu_Order($bean->getData('CmsMenu_Order') + 1);
            $finder->limit(1,0);
            if ($finder->find() == 1 ) {
                $nextBean = $finder->getBean();
                $bean->setData('CmsMenu_Order', $nextBean->getData('CmsMenu_Order'));
                $nextBean->setData('CmsMenu_Order', $nextBean->getData('CmsMenu_Order') - 1 );
                $this->saveBeanWithProcessor($nextBean);
            } else {
                $bean->setData('CmsMenu_Order', 1);
            }
            $this->saveBeanWithProcessor($bean);
        }
    }
}
