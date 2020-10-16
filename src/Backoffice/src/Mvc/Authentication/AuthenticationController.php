<?php


namespace Backoffice\Mvc\Authentication;


use Base\Authentication\User\UserBeanFinder;
use Backoffice\Mvc\Base\BaseController;
use Mezzio\Authentication\UserInterface;
use Mvc\Controller\ControllerRequest;
use Mvc\View\ComponentDataBean;
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
            $alert->setBean(new ComponentDataBean());;
            $alert->setHeading($this->translate('login.error'));
            $alert->addText('login_error', '')->setValue($login_error);
            $this->getView()->addComponent($alert);
        }

        $this->getView()->setHeading($this->translate('login.title'));

        $this->getView()->setLayout('layout/signin');
        $componentDataBean = new ComponentDataBean();
        $componentDataBean->setData('login_username', '');
        $componentDataBean->setData('login_password', '');
        $componentDataBean->setFromArray($this->getControllerRequest()->getAttributes());
        $editComponent = new Edit('');
        $editComponent->setBean($componentDataBean);
        $editComponent->addText('login_username', $this->translate('login.username'))->setType(Text::TYPE_TEXT)->setRequired();
        $editComponent->addText('login_password', $this->translate('login.password'))->setType(Text::TYPE_PASSWORD)->setRequired();
        $editComponent->addSubmit('login', $this->translate('login.submit'));
        $editComponent->addSubmitAttribute('login_token', $this->generateToken('login_token'));
        $editComponent->addSubmitAttribute(ControllerRequest::ATTRIBUTE_REDIRECT, $this->getPathHelper()->setController('index')->setAction('index')->getPath());
        $editComponent->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->addComponent($editComponent);

    }

    public function logoutAction() {
        $this->getSession()->unset(UserInterface::class);
        return $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('auth')->setAction('login')->getPath());
    }

}
