<?php


namespace Backoffice\Mvc\Controller;


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
