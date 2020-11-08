<?php

namespace Pars\Backoffice\Mvc\Index;

use Pars\Backoffice\Mvc\Base\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $this->getView()->setHeading($this->translate('index.title'));
        $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('cmsmenu')->getPath());
    }
}
