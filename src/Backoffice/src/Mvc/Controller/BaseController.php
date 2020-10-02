<?php


namespace Backoffice\Mvc\Controller;


use Backoffice\Authentication\Bean\UserBean;
use Backoffice\Database\DatabaseMiddleware;
use Backoffice\Mvc\Formatter\BackofficeBeanFormatter;
use Backoffice\Mvc\Model\BaseModel;
use Mezzio\Authentication\UserInterface;
use Mezzio\Csrf\CsrfGuardInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessagesInterface;
use Mezzio\Mvc\Controller\AbstractController;
use Mezzio\Mvc\Helper\ValidationHelper;
use Mezzio\Mvc\View\ComponentDataBean;
use Mezzio\Mvc\View\ComponentModel;
use Mezzio\Mvc\View\Components\Alert\Alert;
use Mezzio\Mvc\View\Navigation\Element;
use Mezzio\Mvc\View\Navigation\Navigation;
use Mezzio\Mvc\View\View;
use Mezzio\Mvc\View\ViewModel;
use Mezzio\Session\LazySession;
use Mezzio\Session\SessionMiddleware;

/**
 * Class BaseController
 * @package Backoffice\Mvc\Controller
 * @method BaseModel getModel()
 */
abstract class BaseController extends AbstractController
{
    protected function handleValidationError(ValidationHelper $validationHelper)
    {
        $this->getFlashMessanger()->flash('previousAttributes', $this->getControllerRequest()->getAttributes());
        $this->getFlashMessanger()->flash('validationErrorMap', $validationHelper->getErrorFieldMap());
    }

    /**
     * @return bool
     * @throws \NiceshopsDev\NiceCore\Exception
     */
    protected function handleSubmitSecurity(): bool
    {
        return $this->getGuard()->validateToken($this->getControllerRequest()->getAttribute('token') ?? '');
    }

    /**
     * @return CsrfGuardInterface
     */
    public function getGuard(): CsrfGuardInterface
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
    }

    /**
     * @return FlashMessagesInterface
     */
    public function getFlashMessanger(): FlashMessagesInterface
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
    }

    protected function getValidationErrorMap(): array
    {
        return $this->getFlashMessanger()->getFlash('validationErrorMap', []);
    }

    protected function getPreviousAttributes(): array
    {
        return $this->getFlashMessanger()->getFlash('previousAttributes', []);
    }

    protected function getSession(): LazySession
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
    }

    protected function getUser(): UserBean
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(UserInterface::class) ?? new UserBean();
    }

    protected function initView()
    {
        $this->setView(new View('Backoffice', new ViewModel()));
        $this->getView()->setLayout('layout/dashboard');
        $this->getView()->setBeanFormatter(new BackofficeBeanFormatter());
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

        $navigation->addElement(
            new Element(
                'Rollen',
                $this->getPathHelper()
                    ->setController('userrole')
                    ->setAction('index')
                    ->getPath()
            )
        );

        $navigation->addElement(
            new Element(
                'Update',
                $this->getPathHelper()
                    ->setController('update')
                    ->setAction('index')
                    ->getPath()
            )
        );

        $this->getView()->getViewModel()->addNavigation($navigation);

        // Set Global Template vars
        $this->setTemplateVariable('logoutLink', '/auth/logout');
        if ($this->getUser()->hasData('User_Displayname')) {
            $this->setTemplateVariable('logoutLabel', $this->getUser()->getData('User_Displayname') . ' abmelden');
        } else {
            $this->setTemplateVariable('logoutLabel', 'abmelden');
        }
        $this->setTemplateVariable('searchAction', '/index/search');
        $this->setTemplateVariable('searchLabel', 'Suchen');
    }

    protected function initModel()
    {
        $this->getModel()->setDbAdapter($this->getControllerRequest()->getServerRequest()->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE));
        $this->getModel()->init();
    }

    /**
     * @return mixed|void
     */
    public function end()
    {
        $this->setTemplateVariable('token', $this->getGuard()->generateToken());
    }

    /**
     * @param \Throwable $exception
     * @return mixed|void
     * @throws \Throwable
     */
    public function error(\Throwable $exception)
    {
        $alert = new Alert('', new ComponentModel());
        $alert->setHeading("Es ist ein Fehler aufgetreten.");
        if ($this->getControllerResponse()->getStatusCode() == 404) {
            $alert->setStyle(Alert::STYLE_DARK);
        } else {
            $alert->setStyle(Alert::STYLE_DANGER);
        }

        $alert->addText('message', 'Fehler');
        $alert->addText('', 'Details')->setValue("{file}:{line}");
        $alert->addText('trace', 'Trace');
        $alert->getComponentModel()->setComponentDataBean(new ComponentDataBean());
        $alert->getComponentModel()->getComponentDataBean()->setData('message', $exception->getMessage());
        $alert->getComponentModel()->getComponentDataBean()->setData('file', $exception->getFile());
        $alert->getComponentModel()->getComponentDataBean()->setData('line', $exception->getLine());
        $trace = explode(PHP_EOL, $exception->getTraceAsString());
        $trace = array_slice($trace, 0, 5);
        $alert->getComponentModel()->getComponentDataBean()->setData('trace', implode('<br>', $trace));

        $this->getView()->addComponent($alert, true);
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

}
