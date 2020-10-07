<?php


namespace Backoffice\Mvc\Base;


use Backoffice\Authentication\Bean\UserBean;
use Backoffice\Database\DatabaseMiddleware;
use Mezzio\Authentication\UserInterface;
use Mezzio\Csrf\CsrfGuardInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessagesInterface;
use Mvc\Controller\AbstractController;
use Mvc\Controller\ControllerRequest;
use Mvc\Helper\PathHelper;
use Mvc\Helper\ValidationHelper;
use Mvc\View\ComponentDataBean;
use Mvc\View\ComponentModel;
use Mvc\View\Components\Alert\Alert;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Overview\Overview;
use Mvc\View\Components\Toolbar\Toolbar;
use Mvc\View\Navigation\Element;
use Mvc\View\Navigation\Navigation;
use Mvc\View\Navigation\PageNavigation;
use Mvc\View\Navigation\PageNavigationElement;
use Mvc\View\View;
use Mvc\View\ViewModel;
use Mezzio\Session\LazySession;
use Mezzio\Session\SessionMiddleware;
use NiceshopsDev\Bean\BeanFormatter\BeanFormatterInterface;
use NiceshopsDev\NiceCore\Attribute\AttributeAwareInterface;
use NiceshopsDev\NiceCore\Attribute\AttributeTrait;

/**
 * Class BaseController
 * @package Backoffice\Mvc\Controller
 * @method BaseModel getModel()
 */
abstract class BaseController extends AbstractController implements AttributeAwareInterface
{
    use AttributeTrait;

    public const ATTRIBUTE_CREATE_PERMISSION = 'create_permission';
    public const ATTRIBUTE_EDIT_PERMISSION = 'edit_permission';
    public const ATTRIBUTE_DELETE_PERMISSION = 'delete_permission';


    public function setPermissions(string $create, string $edit, string $delete)
    {
        $this->setAttribute(self::ATTRIBUTE_CREATE_PERMISSION, $create);
        $this->setAttribute(self::ATTRIBUTE_EDIT_PERMISSION, $edit);
        $this->setAttribute(self::ATTRIBUTE_DELETE_PERMISSION, $delete);
        if ($this->checkPermission($create)) {
            $this->getModel()->addOption(BaseModel::OPTION_CREATE_ALLOWED);
        }
        if ($this->checkPermission($edit)) {
            $this->getModel()->addOption(BaseModel::OPTION_EDIT_ALLOWED);
        }
        if ($this->checkPermission($delete)) {
            $this->getModel()->addOption(BaseModel::OPTION_DELETE_ALLOWED);
        }
    }

    /**
     * @param string $id
     * @param int $index
     * @return mixed|void
     */
    protected function handleNavigationState(string $id, int $index)
    {
        $this->getSession()->set($id, $index);
    }

    /**
     * @param string $id
     * @return mixed
     */
    protected function getNavigationState(string $id): int
    {
        return intval($this->getSession()->get($id)) ?? 0;
    }

    /**
     * @param ValidationHelper $validationHelper
     * @return mixed|void
     */
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

    /**
     * @param string $permission
     * @return bool
     */
    protected function checkPermission(string $permission): bool
    {
        return in_array($permission, $this->getUser()->getPermission_List());
    }

    protected function initView()
    {
        $this->setView(new View('Backoffice', new ViewModel()));
        $this->getView()->setLayout('layout/dashboard');
        $this->getView()->setBeanFormatter(new BackofficeBeanFormatter());
        $this->getView()->setPermissionList($this->getUser()->getPermission_List());

        $navigation = new Navigation('System');
        $navigation->setPermissionList($this->getUser()->getPermission_List());
        $element =  new Element(
            'Benutzer',
            $this->getPathHelper()
                ->setController('user')
                ->setAction('index')
                ->getPath()
        );
        $element->setPermission('user');
        $navigation->addElement($element);
        $element = new Element(
            'Rollen',
            $this->getPathHelper()
                ->setController('role')
                ->setAction('index')
                ->getPath()
        );
        $element->setPermission('role');
        $navigation->addElement($element);

        $element =  new Element(
            'Update',
            $this->getPathHelper()
                ->setController('update')
                ->setAction('index')
                ->getPath()
        );
        $element->setPermission('update');
        $navigation->addElement($element);

        $this->getView()->addNavigation($navigation);

        // Set Global Template vars
        $this->setTemplateVariable('logoutLink', $this->getPathHelper()->setController('auth')->setAction('logout')->getPath());
        if ($this->getUser()->hasData('User_Displayname')) {
            $this->setTemplateVariable('logoutLabel', $this->getUser()->getData('User_Displayname') . ' abmelden');
        } else {
            $this->setTemplateVariable('logoutLabel', 'abmelden');
        }
        $this->setTemplateVariable('searchAction', $this->getPathHelper()->setController('index')->setAction('search')->getPath());
        $this->setTemplateVariable('searchLabel', 'Suchen');
    }

    protected function initModel()
    {
        $this->getModel()->setDbAdapter($this->getControllerRequest()->getServerRequest()->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE));
        $this->getModel()->setUser($this->getUser());
        $this->getModel()->init();
    }

    /**
     * @return mixed|void
     */
    public function end()
    {
        $this->setTemplateVariable('token', $this->getGuard()->generateToken());
        $validationHelper = new ValidationHelper();
        $validationHelper->addErrorFieldMap($this->getValidationErrorMap());
        if (count($validationHelper->getErrorList('Permission'))) {
            $alert = new Alert();
            $alert->setHeading('Zugriff verweigert!');
            $alert->getComponentModel()->setComponentDataBean(new ComponentDataBean());
            foreach ($validationHelper->getErrorList('Permission') as $item) {
                $alert->addText('', '')->setValue($item);
            }
            $this->getView()->addComponent($alert, true);
        }
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



    protected function initOverviewTemplate(BeanFormatterInterface $formatter)
    {
        $this->getView()->getViewModel()->setTitle('Übersicht');
        $toolbar = new Toolbar();
        $toolbar->getComponentModel()->setComponentDataBean(new ComponentDataBean());
        $button = $toolbar->addButton($this->getPathHelper()->setAction('create')->getPath(), 'Hinzufügen');
        if ($this->hasAttribute(self::ATTRIBUTE_CREATE_PERMISSION)) {
            $button->setPermission($this->getAttribute(self::ATTRIBUTE_CREATE_PERMISSION));
        }
        $this->getView()->addComponent($toolbar);
        $overview = new Overview();
        if (null !== $formatter) {
            $overview->setBeanFormatter($formatter);
        }
        $path = $this->getDetailPath();
        $overview->addDetailIcon($path->setAction('detail')->getPath(false))
            ->setWidth(122);
        $editButton = $overview->addEditIcon($path->setAction('edit')->getPath(false));
        if ($this->hasAttribute(self::ATTRIBUTE_EDIT_PERMISSION)) {
            $editButton->setPermission($this->getAttribute(self::ATTRIBUTE_EDIT_PERMISSION));
        }
        $deleteButton = $overview->addDeleteIcon($path->setAction('delete')->getPath(false));
        if ($this->hasAttribute(self::ATTRIBUTE_DELETE_PERMISSION)) {
            $deleteButton->setPermission($this->getAttribute(self::ATTRIBUTE_DELETE_PERMISSION));
        }
        $this->addOverviewFields($overview);

        if ($this->getModel()->getFinder()->hasLimit()) {
            $pages = $this->getModel()->getFinder()->count() / $this->getModel()->getFinder()->getLimit();
            $pageNavigation = new PageNavigation();
            for ($i = 0; $i < $pages; $i++) {
                $element = new PageNavigationElement();
                $element->setLink($this->getPathHelper()->setParams([ControllerRequest::ATTRIBUTE_LIMIT => $this->getModel()->getFinder()->getLimit(), ControllerRequest::ATTRIBUTE_PAGE => $i + 1])->getPath());
                $pageNavigation->addElement($element);
            }
            if ($this->getControllerRequest()->hasPage()) {
                $page = $this->getControllerRequest()->getPage();
                $page = $page > 0 ? $page : 1;
                $pageNavigation->setActive($page - 1);
            }
            $overview->getComponentModel()->setPageNavigation($pageNavigation);
        }

        $this->getView()->addComponent($overview);
        return $overview;
    }

    protected function initDetailTemplate()
    {
        $this->getView()->getViewModel()->setTitle('Details');
        if (!count($this->getControllerRequest()->getViewIdMap())) {
            $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('index')->getPath());
        }
        $detail = new Detail();
        $this->addDetailFields($detail);
        $this->getView()->addComponent($detail);
        return $detail;
    }

    protected function getIndexRedirectLink()
    {
        return $this->getPathHelper()->setAction('index')->getPath();
    }

    protected function initCreateTemplate(string $redirect = null)
    {
        if (null === $redirect) {
            $redirect = $this->getIndexRedirectLink();
        }
        $this->getView()->getViewModel()->setTitle('Hinzufügen');
        $edit = new Edit();
        $this->addEditFields($edit);
        $edit->addSubmit(ControllerRequest::SUBMIT_MODE_CREATE, 'Speichern', $redirect);
        $edit->addCancel($redirect, 'Abbrechen', true);
        $edit->getComponentModel()->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->addComponent($edit);
        return $edit;
    }

    protected function initEditTemplate(string $redirect = null)
    {
        if (null === $redirect) {
            $redirect = $this->getIndexRedirectLink();
        }
        $this->getView()->getViewModel()->setTitle('Bearbeiten');
        $edit = new Edit();
        $this->addEditFields($edit);
        $edit->addSubmit(ControllerRequest::SUBMIT_MODE_SAVE, 'Speichern', $redirect);
        $edit->addCancel($redirect, 'Abbrechen', true);
        $edit->getComponentModel()->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->addComponent($edit);
        return $edit;
    }


    public function initDeleteTemplate(string $redirect = null)
    {
        if (null === $redirect) {
            $redirect = $this->getIndexRedirectLink();
        }
        $this->getView()->getViewModel()->setTitle('Löschen');
        $alert = new Alert();
        $alert->addText("", "")->setValue('Sind sie sicher, dass sie den Eintrag löschen wollen?');
        $alert->getComponentModel()->setComponentDataBean(new ComponentDataBean());
        $this->getView()->addComponent($alert);
        $edit = new Edit();
        $edit->addSubmit(ControllerRequest::SUBMIT_MODE_DELETE, 'Löschen', $redirect);
        $edit->addCancel($redirect, 'Abbrechen', true);
        $this->getView()->addComponent($edit);
        return $edit;
    }


    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper();
    }

    /**
     * @param Overview $overview
     */
    protected function addOverviewFields(Overview $overview): void
    {

    }

    /**
     * @param Detail $detail
     * @return mixed
     */
    protected function addDetailFields(Detail $detail): void
    {

    }

    /**
     * @param Edit $edit
     * @return mixed
     */
    protected function addEditFields(Edit $edit): void
    {

    }

}
