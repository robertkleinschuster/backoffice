<?php


namespace Backoffice\Mvc\Index;


use Backoffice\Mvc\Base\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $this->getView()->getViewModel()->setTitle($this->translate('index.title'));
    }

    public function searchAction() {
        $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('index')->getPath());
    }

}
