<?php

namespace Pars\Backoffice\Mvc\Translation;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Localization\Locale\LocaleBeanFinder;
use Pars\Base\Translation\TranslationLoader\TranslationBeanFinder;
use Pars\Base\Translation\TranslationLoader\TranslationBeanProcessor;
use Pars\Mvc\Parameter\IdParameter;
use Pars\Mvc\Parameter\SubmitParameter;

class TranslationModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new TranslationBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new TranslationBeanProcessor($this->getDbAdpater()));
    }

    /**
     * @return array
     */
    public function getLocale_Options(): array
    {
        $options = [];
        $finder = new LocaleBeanFinder($this->getDbAdpater());
        $finder->setLocale_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('Locale_Code')] = $bean->getData('Locale_Name');
        }
        return $options;
    }

    /**
     * @param SubmitParameter $submitParameter
     * @param IdParameter $idParameter
     * @param array $attribute_List
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    public function submit(SubmitParameter $submitParameter, IdParameter $idParameter, array $attribute_List)
    {
        parent::submit($submitParameter, $idParameter, $attribute_List);
        $this->getTranslator()->clearCache($attributes['Translation_Namespace'] ?? 'default', $attributes['Locale_Code'] ?? $this->getTranslator()->getLocale());
    }
}
