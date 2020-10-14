<?php


namespace Backoffice\Mvc\Index;


use Backoffice\Mvc\Base\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $this->getView()->setHeading($this->translate('index.title'));
    }

}
