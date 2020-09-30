<?php


namespace Backoffice\Mvc\Controller;


use Mezzio\Authentication\UserInterface;
use Mezzio\Mvc\Controller\ControllerRequest;
use Mezzio\Mvc\View\ComponentDataBean;
use Mezzio\Mvc\View\ComponentModel;
use Mezzio\Mvc\View\Components\Alert\Alert;
use Mezzio\Mvc\View\Components\Edit\Edit;
use Mezzio\Mvc\View\Components\Edit\Fields\Text;


class AuthenticationController extends BaseController
{
    public function loginAction() {
        if ($this->getSession()->has(UserInterface::class)) {
            $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('index')->setAction('index')->getPath());
            return;
        }
        $login_error = $this->getFlashMessanger()->getFlash('login_error');
        if ($login_error) {
            $alert = new Alert('Fehler bei der Anmeldung', new ComponentModel());
            $alert->getComponentModel()->setComponentDataBean(new ComponentDataBean());;
            $alert->addText('', 'login_error')->setValue($login_error);
            $this->getView()->addComponent($alert);
        }

        $this->getView()->getViewModel()->setTitle('Anmelden');
        $this->getView()->setLayout('layout/default');
        $componentModel = new ComponentModel();
        $componentDataBean = new ComponentDataBean();
        $componentDataBean->setData('login_username', '');
        $componentDataBean->setData('login_password', '');
        $componentDataBean->setFromArray($this->getControllerRequest()->getAttributes());
        $componentModel->setComponentDataBean($componentDataBean);
        $editComponent = new Edit('', $componentModel);
        $editComponent->addText('Benutzername', 'login_username')->setType(Text::TYPE_TEXT)->setRequired();
        $editComponent->addText('Passwort', 'login_password')->setType(Text::TYPE_PASSWORD)->setRequired();
        $editComponent->addSubmit('Anmelden', 'login');
        $editComponent->addAttribute('login_token', $this->getGuard()->generateToken('login_token'));
        $editComponent->addAttribute(ControllerRequest::ATTRIBUTE_REDIRECT, $this->getPathHelper()->setController('index')->setAction('index')->getPath());
        $editComponent->getComponentModel()->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->addComponent($editComponent);

    }

    public function logoutAction() {
        $this->getSession()->clear();
        return $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('auth')->setAction('login')->getPath());
    }


}
