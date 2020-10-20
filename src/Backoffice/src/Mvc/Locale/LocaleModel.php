<?php


namespace Backoffice\Mvc\Locale;


use Backoffice\Mvc\Base\BaseModel;
use Base\Localization\Locale\LocaleBeanFinder;
use Base\Localization\Locale\LocaleBeanProcessor;

class LocaleModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new LocaleBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new LocaleBeanProcessor($this->getDbAdpater()));
    }


    public function orderUp()
    {
        $bean = $this->getFinder()->getBean();
        if ($bean->hasData('Locale_Order') && $bean->getData('Locale_Order') > 1) {
            $finder = new LocaleBeanFinder($this->getDbAdpater());
            $finder->setLocale_Order($bean->getData('Locale_Order') - 1);
            $finder->limit(1,0);
            $finder->find();
            $previuousBean = $finder->getBean();
            $bean->setData('Locale_Order', $previuousBean->getData('Locale_Order'));
            $previuousBean->setData('Locale_Order', $previuousBean->getData('Locale_Order') + 1 );
            $this->saveBeanWithProcessor($bean);
            $this->saveBeanWithProcessor($previuousBean);
        }
    }

    public function orderDown()
    {
        $bean = $this->getFinder()->getBean();
        $finder = new LocaleBeanFinder($this->getDbAdpater());
        if ($bean->hasData('Locale_Order') && $bean->getData('Locale_Order') < $finder->count()) {
            $finder->setLocale_Order($bean->getData('Locale_Order') + 1);
            $finder->limit(1,0);
            $finder->find();
            $nextBean = $finder->getBean();
            $bean->setData('Locale_Order', $nextBean->getData('Locale_Order'));
            $nextBean->setData('Locale_Order', $nextBean->getData('Locale_Order') - 1 );
            $this->saveBeanWithProcessor($bean);
            $this->saveBeanWithProcessor($nextBean);
        }
    }
}
