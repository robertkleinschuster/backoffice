<?php


namespace Backoffice\Mvc\Controller;


use Backoffice\Database\DatabaseMiddleware;
use Backoffice\Mvc\Model\BaseModel;
use Mezzio\Csrf\CsrfGuardInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Mvc\Controller\AbstractController;
use Mezzio\Mvc\Controller\ErrorController;
use Mezzio\Mvc\View\ComponentDataBean;
use Mezzio\Mvc\View\ComponentDataBeanList;
use Mezzio\Mvc\View\ComponentModel;
use Mezzio\Mvc\View\Components\Alert\Alert;
use Mezzio\Mvc\View\Components\Detail\Detail;
use Mezzio\Mvc\View\Navigation\Element;
use Mezzio\Mvc\View\Navigation\Navigation;
use Mezzio\Mvc\View\View;
use Mezzio\Mvc\View\ViewModel;
use Mezzio\Mvc\View\ViewRenderer;

/**
 * Class BaseController
 * @package Backoffice\Mvc\Controller
 * @method BaseModel getModel()
 */
abstract class BaseController extends AbstractController
{

    /**
     * @throws \NiceshopsDev\Bean\BeanException
     */
    public function init()
    {
        $this->getModel()->setDbAdapter($this->getControllerRequest()->getServerRequest()->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE));
        // Set Global Template vars
        $this->setTemplateVariable('logoutLink', '/auth/logout');
        $this->setTemplateVariable('logoutLabel', 'Logout');
        $this->setTemplateVariable('searchAction', '/index/search');
        $this->setTemplateVariable('searchLabel', 'Suchen');

        $this->setView(new View('Backoffice', new ViewModel()));
        $this->getView()->setLayout('layout/dashboard');
        $navigation = new Navigation('System');
        $navigation->addElement(
            new Element(
                'Benutzer',
                $this->getPathHelper()
                    ->setController('user')
                    ->setAction('overview')
                    ->getPath()
            )
        );
        $this->getView()->getViewModel()->addNavigation($navigation);
    }

    public function getGuard(): CsrfGuardInterface
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
    }

    /**
     * @return bool
     * @throws \NiceshopsDev\NiceCore\Exception
     */
    public function validateToken(): bool
    {
        $token = $this->getControllerRequest()->getAttribute('token');
        $valid =  $this->getGuard()->validateToken($token ?? '');
        $this->getModel()->getValidationHelper()->addError('', 'Session ist ungültig.');
        return $valid;
    }

    /**
     * @param $controller
     * @param $action
     */
    public function setActiveNavigation(string $controller, string $action) {
        foreach ($this->getView()->getViewModel()->getNavigationList() as $item) {
            foreach ($item->getElementList() as $element) {
                if ($element->getLink() == $this->getPathHelper()
                        ->setController($controller)
                        ->setAction($action)
                        ->getPath()) {
                    $element->setActive(true);
                    return;
                }
            }
        }
    }

    /**
     * @return mixed|void
     */
    public function end()
    {
        if ($this->getModel()->getValidationHelper()->hasError()) {
            $alert = new Alert('Validierungsfehler', new ComponentModel());
            foreach ($this->getModel()->getValidationHelper()->getErrorList() as $error) {
                $alert->addText('Fehler: ', 'error')->setValue($error);
            }
            $alert->getComponentModel()->setComponentDataBean(new ComponentDataBean());
            $this->getView()->addComponent($alert, true);
        }
        $this->setTemplateVariable('token', $this->getGuard()->generateToken());
    }

    /**
     * @param \Throwable $exception
     * @return mixed|void
     * @throws \Throwable
     */
    public function error(\Throwable $exception)
    {
        $alert = new Alert("Es ist ein Fehler aufgetreten.", new ComponentModel());
        if ($this->getControllerResponse()->getStatusCode() == 404) {
            $alert->setStyle(Alert::STYLE_DARK);
        } else {
            $alert->setStyle(Alert::STYLE_DANGER);
        }

        $alert->addText('Fehler', 'message');
        $alert->addText('Details', '')->setValue("{file}:{line}");
        $alert->getComponentModel()->setComponentDataBean(new ComponentDataBean());
        $alert->getComponentModel()->getComponentDataBean()->setData('message', $exception->getMessage());
        $alert->getComponentModel()->getComponentDataBean()->setData('file', $exception->getFile());
        $alert->getComponentModel()->getComponentDataBean()->setData('line', $exception->getLine());
        $this->getView()->addComponent($alert);
    }
}
