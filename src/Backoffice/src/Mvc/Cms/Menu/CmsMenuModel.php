<?php


namespace Backoffice\Mvc\Cms\Menu;


use Backoffice\Mvc\Base\BaseModel;
use Base\Cms\Menu\CmsMenuBeanFinder;
use Base\Cms\Menu\CmsMenuBeanProcessor;
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
}
