<?php


namespace Backoffice\Mvc\Controller;


use Mezzio\Mvc\View\ComponentDataBean;
use Mezzio\Mvc\View\ComponentModel;
use Mezzio\Mvc\View\Components\Edit\Edit;
use Mezzio\Mvc\View\Components\Edit\Fields\Button;
use Mezzio\Mvc\View\Components\Edit\Fields\Text;
use Mezzio\Mvc\View\Navigation\Element;
use Mezzio\Mvc\View\Navigation\Navigation;
use Mezzio\Mvc\View\View;
use Mezzio\Mvc\View\ViewModel;

class AuthenticationController extends BaseController
{

    public function loginAction() {
        $viewModel = new ViewModel();
        $view = new View('Backoffice', $viewModel);
        $viewModel->setTitle('Login');
        $navigation = new Navigation('Menü');

        $navigation->addElement(new Element('Home', '/index/index'));

        $viewModel->addNavigation($navigation);
        $view->setLayout('layout/default');
        $componentModel = new ComponentModel();
        $componentDataBean = new ComponentDataBean();
        $componentDataBean->setData('email', '');
        $componentDataBean->setData('password', '');
        $componentDataBean->setFromArray($this->getControllerRequest()->getAttributes());
        $componentModel->setComponentDataBean($componentDataBean);
        $editComponent = new Edit('Login', $componentModel);
        $editComponent->addText('Benutzername', 'email')->setType(Text::TYPE_TEXT)->setRequired();
        $editComponent->addText('Passwort', 'password')->setType(Text::TYPE_PASSWORD)->setRequired();
        $editComponent->addButton('Login', 'login')->setType(Button::TYPE_SUBMIT);
        $view->addComponent($editComponent);
        $this->setView($view);

    }

    public function logoutAction() {
        return $this->getControllerResponse()->setRedirect('/auth/login');
    }


}
