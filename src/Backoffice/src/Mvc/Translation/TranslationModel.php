<?php


namespace Backoffice\Mvc\Translation;


use Backoffice\Mvc\Base\BaseModel;
use Base\Localization\Locale\LocaleBeanFinder;
use Base\Translation\TranslationLoader\TranslationBeanFinder;
use Base\Translation\TranslationLoader\TranslationBeanProcessor;

class TranslationModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new TranslationBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new TranslationBeanProcessor($this->getDbAdpater()));
    }

    /**
     * @return array
     * @throws \NiceshopsDev\Bean\BeanException
     */
    public function getLocale_Options(): array {
        $options = [];
        $finder = new LocaleBeanFinder($this->getDbAdpater());
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('Locale_Code')] = $bean->getData('Locale_Name');
        }
        return $options;
    }
}
