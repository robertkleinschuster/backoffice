<?php


namespace Backoffice\Mvc\Translation;


use Backoffice\Mvc\Base\BaseModel;
use Base\Translation\TranslationLoader\TranslationBeanFinder;
use Base\Translation\TranslationLoader\TranslationBeanProcessor;

class TranslationModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new TranslationBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new TranslationBeanProcessor($this->getDbAdpater()));
    }

}
