<?php

namespace Pars\Backoffice\Mvc\Locale;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Localization\Locale\LocaleBeanFinder;
use Pars\Base\Localization\Locale\LocaleBeanProcessor;

class LocaleModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new LocaleBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new LocaleBeanProcessor($this->getDbAdpater()));
    }

}
