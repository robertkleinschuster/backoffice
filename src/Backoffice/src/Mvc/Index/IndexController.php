<?php


namespace Backoffice\Mvc\Index;


use Backoffice\Mvc\Base\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $this->getView()->getViewModel()->setTitle('Startseite');

    }

    public function searchAction() {
        $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('index')->getPath());
    }

}
