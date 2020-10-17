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


}
