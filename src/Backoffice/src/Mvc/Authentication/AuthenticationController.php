<?php


namespace Backoffice\Mvc\Authentication;


use Base\Authentication\Bean\UserBeanFinder;
use Backoffice\Mvc\Base\BaseController;
use Mezzio\Authentication\UserInterface;
use Mvc\Controller\ControllerRequest;
use Mvc\View\ComponentDataBean;
use Mvc\View\ComponentModel;
use Mvc\View\Components\Alert\Alert;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Edit\Fields\Text;


class AuthenticationController extends BaseController
{
    public function loginAction() {
        try {
            $userFinder = new UserBeanFinder($this->getModel()->getDbAdpater());
            $count = $userFinder->count();
        } catch (\Exception $ex) {
            $count = 0;
        }
        if ($count == 0) {
            $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('setup')->setAction('index')->getPath());
        }
        if ($this->getSession()->has(UserInterface::class)) {
            $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('index')->setAction('index')->getPath());
            return;
        }
        $login_error = $this->getFlashMessanger()->getFlash('login_error');
        if ($login_error) {
            $alert = new Alert();
            $alert->getComponentModel()->setComponentDataBean(new ComponentDataBean());;
            $alert->setHeading('Fehler bei der Anmeldung');
            $alert->addText('login_error', '')->setValue($login_error);
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
        $editComponent->addText('login_username', 'Benutzername')->setType(Text::TYPE_TEXT)->setRequired();
        $editComponent->addText('login_password', 'Passwort')->setType(Text::TYPE_PASSWORD)->setRequired();
        $editComponent->addSubmit('login', 'Anmelden');
        $editComponent->addSubmitAttribute('login_token', $this->getGuard()->generateToken('login_token'));
        $editComponent->addSubmitAttribute(ControllerRequest::ATTRIBUTE_REDIRECT, $this->getPathHelper()->setController('index')->setAction('index')->getPath());
        $editComponent->getComponentModel()->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->addComponent($editComponent);

    }

    public function logoutAction() {
        $this->getSession()->clear();
        return $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('auth')->setAction('login')->getPath());
    }

}
