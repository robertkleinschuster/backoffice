<?php


namespace Backoffice\Mvc\Cms\Menu;


use Backoffice\Mvc\Base\BaseModel;
use Base\Cms\Menu\CmsMenuBeanFinder;
use Base\Cms\Menu\CmsMenuBeanProcessor;

class CmsMenuModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new CmsMenuBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new CmsMenuBeanProcessor($this->getDbAdpater()));
        $this->getFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }

}
