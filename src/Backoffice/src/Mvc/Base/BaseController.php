<?php


namespace Backoffice\Mvc\Base;


use Base\Authentication\User\UserBean;
use Base\Database\DatabaseMiddleware;
use Base\Logging\LoggingMiddleware;
use Base\Translation\TranslatorMiddleware;
use Laminas\Db\Adapter\Profiler\ProfilerInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Csrf\CsrfGuardInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessagesInterface;
use Mvc\Controller\AbstractController;
use Mvc\Controller\ControllerRequest;
use Mvc\Controller\ControllerResponse;
use Mvc\Helper\PathHelper;
use Mvc\Helper\ValidationHelper;
use Mvc\View\ComponentDataBean;
use Mvc\View\Components\Alert\Alert;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Overview\Overview;
use Mvc\View\Components\Pagination\Pagination;
use Mvc\View\Components\Toolbar\Toolbar;
use Mvc\View\Navigation\Element;
use Mvc\View\Navigation\Navigation;
use Mvc\View\View;
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


    protected function getTranslator(): TranslatorInterface
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(TranslatorMiddleware::TRANSLATOR_ATTRIBUTE);
    }

    /**
     * @param string $code
     * @return string
     */
    protected function translate(string $code)
    {
        return $this->getTranslator()->translate($code, 'backoffice');
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
        return $this->validateToken('submit_token', $this->getControllerRequest()->getAttribute('token') ?? '');
    }

    /**
     * @return CsrfGuardInterface
     */
    protected function getGuard(): CsrfGuardInterface
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
    }

    protected function validateToken(string $name, $token) {
        $result = $this->getGuard()->validateToken($token, $name);
        $this->generateToken($name);
        return $result;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function generateToken(string $name): string
    {
        if (!$this->getSession()->get($name, false)) {
            return $this->getGuard()->generateToken($name);
        } else {
            return $this->getSession()->get($name);
        }
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
        $this->setView(new View('layout/dashboard'));
        $this->getView()->setTitle('Backoffice');
        $this->getView()->setBeanFormatter(new BackofficeBeanFormatter());
        $this->getView()->setPermissionList($this->getUser()->getPermission_List());

        $navigation = new Navigation($this->translate('navigation.content'));
        $navigation->setPermissionList($this->getUser()->getPermission_List());

        $element =  new Element(
            $this->translate('navigation.content.cmsmenu'),
            $this->getPathHelper()
                ->setController('cmsmenu')
                ->setAction('index')
                ->getPath()
        );
        #   $element->setPermission('cmsmenu');
        $navigation->addElement($element);

        $element =  new Element(
            $this->translate('navigation.content.cmssite'),
            $this->getPathHelper()
                ->setController('cmssite')
                ->setAction('index')
                ->getPath()
        );
     #   $element->setPermission('cmssite');
        $navigation->addElement($element);

        $element =  new Element(
            $this->translate('navigation.content.cmsparagraph'),
            $this->getPathHelper()
                ->setController('cmsparagraph')
                ->setAction('index')
                ->getPath()
        );
     #   $element->setPermission('cmsparagraph');
        $navigation->addElement($element);

        $element =  new Element(
            $this->translate('navigation.content.translation'),
            $this->getPathHelper()
                ->setController('translation')
                ->setAction('index')
                ->getPath()
        );
        $element->setPermission('translation');
        $navigation->addElement($element);
        $this->getView()->addNavigation($navigation);

        $navigation = new Navigation($this->translate('navigation.system'));
        $navigation->setPermissionList($this->getUser()->getPermission_List());

        $element =  new Element(
            $this->translate('navigation.system.user'),
            $this->getPathHelper()
                ->setController('user')
                ->setAction('index')
                ->getPath()
        );
        $element->setPermission('user');
        $navigation->addElement($element);
        $element = new Element(
            $this->translate('navigation.system.role'),
            $this->getPathHelper()
                ->setController('role')
                ->setAction('index')
                ->getPath()
        );
        $element->setPermission('role');
        $navigation->addElement($element);

        $element = new Element(
            $this->translate('navigation.system.locale'),
            $this->getPathHelper()
                ->setController('locale')
                ->setAction('index')
                ->getPath()
        );
        $element->setPermission('locale');
        $navigation->addElement($element);

        $element =  new Element(
            $this->translate('navigation.system.update'),
            $this->getPathHelper()
                ->setController('update')
                ->setAction('index')
                ->getPath()
        );
        $element->setPermission('update');
        $navigation->addElement($element);

        $this->getView()->addNavigation($navigation);

        $navigation = new Navigation($this->translate('navigation.account'), Navigation::POSITION_HEADER);
        $navigation->setPermissionList($this->getUser()->getPermission_List());
        $element =  new Element(
            $this->translate('navigation.account.logout'),
            $this->getPathHelper()
                ->setController('auth')
                ->setAction('logout')
                ->getPath()
        );
        $navigation->addElement($element);
        $this->getView()->addNavigation($navigation);



        $this->getView()->setToolbar(new Toolbar());
        $this->getView()->getToolbar()->setBean(new ComponentDataBean());
    }

    protected function initModel()
    {
        $this->getModel()->setDbAdapter($this->getControllerRequest()->getServerRequest()->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE));
        $this->getModel()->setUser($this->getUser());
        $this->getModel()->setTranslator($this->getTranslator());
        $this->getModel()->init();
    }

    /**
     * @return mixed|void
     */
    public function end()
    {
        $this->getView()->setData('token', $this->generateToken('submit_token'));
        $validationHelper = new ValidationHelper();
        $validationHelper->addErrorFieldMap($this->getValidationErrorMap());
        if (count($validationHelper->getErrorList('Permission'))) {
            $alert = new Alert();
            $alert->setHeading('Zugriff verweigert!');
            $alert->setBean(new ComponentDataBean());
            foreach ($validationHelper->getErrorList('Permission') as $item) {
                $alert->addText('', '')->setValue($item);
            }
            $this->getView()->addComponent($alert, true);
        }

        if ($this->getControllerRequest()->getAttribute('debug') == 'true') {
            $this->getView()->getToolbar()->addButton($this->getPathHelper()->setParams(['debug' => 'false'])->getPath(), 'Debug')->setPermission('debug');

            $this->getSession()->set('debug', true);
        } elseif ($this->getControllerRequest()->getAttribute('debug') == 'false') {
            $this->getView()->getToolbar()->addButton($this->getPathHelper()->setParams(['debug' => 'true'])->getPath(), 'Debug')->setPermission('debug');

            $this->getSession()->set('debug', false);
        } else {
            $this->getView()->getToolbar()->addButton($this->getPathHelper()->setParams(['debug' => 'true'])->getPath(), 'Debug')->setPermission('debug');

        }

        $profiler = $this->getModel()->getDbAdpater()->getProfiler();
        if ($profiler instanceof ProfilerInterface && $this->getSession()->get('debug', false)) {
            $profiles = $profiler->getProfiles();
            $alert = new Alert();
            $alert->setHeading('Debug');
            $alert->setStyle(Alert::STYLE_WARNING);
            $alert->setBean(new ComponentDataBean());
            $alert->addText('queryCount', '')->setValue('Abfragen: ' . count($profiles) . '<br>' . array_sum(array_column($profiles, 'elapse')) . ' ms');
            foreach ($profiles as $profile) {
                $alert->addText('sql', '')->setValue($profile['sql'] . "<br>{$profile['elapse']} ms");
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
        $this->getControllerRequest()->getServerRequest()->getAttribute(LoggingMiddleware::LOGGER_ATTRIBUTE)->error("Error: ", ['exception' => $exception]);

        $alert = new Alert('');
        $alert->setHeading("Es ist ein Fehler aufgetreten.");
        if ($this->getControllerResponse()->getStatusCode() == 404) {
            $alert->setStyle(Alert::STYLE_DARK);
        } else {
            $alert->setStyle(Alert::STYLE_DANGER);
        }

        $alert->addText('message', 'Fehler');
        $alert->addText('', 'Details')->setValue("{file}:{line}");
        $alert->addText('trace', 'Trace');
        $alert->setBean(new ComponentDataBean());
        $alert->getBean()->setData('message', $exception->getMessage());
        $alert->getBean()->setData('file', $exception->getFile());
        $alert->getBean()->setData('line', $exception->getLine());
        $trace = explode(PHP_EOL, $exception->getTraceAsString());
        $trace = array_slice($trace, 0, 5);
        $alert->getBean()->setData('trace', implode('<br>', $trace));
        if ($this->hasView()) {
            $this->getView()->addComponent($alert, true);
        } else {
            $this->getControllerResponse()->setBody($exception->getMessage());
            $this->getControllerResponse()->removeOption(ControllerResponse::OPTION_RENDER_RESPONSE);
        }
    }

    public function unauthorized()
    {
        $this->setTemplate('error/404');
    }


    protected function initOverviewTemplate(?BeanFormatterInterface $formatter = null)
    {
        $this->getView()->setHeading($this->translate('overview.title'));
        $toolbar = new Toolbar();
        $toolbar->setBean(new ComponentDataBean());
        $button = $toolbar->addButton($this->getPathHelper()->setAction('create')->getPath(), $this->translate('overview.create'));
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
        $this->getView()->addComponent($overview);

        if ($this->getModel()->getFinder()->hasLimit()) {
            $pages = $this->getModel()->getFinder()->count() / $this->getModel()->getFinder()->getLimit();
            if ($pages > 1) {
                $pagination = new Pagination();
                for ($i = 0; $i < $pages; $i++) {
                    $link = $this->getPathHelper()->setParams([ControllerRequest::ATTRIBUTE_LIMIT => $this->getModel()->getFinder()->getLimit(), ControllerRequest::ATTRIBUTE_PAGE => $i + 1])->getPath();
                    $pagination->addLink($link);
                }
                if ($this->getControllerRequest()->hasPage()) {
                    $page = $this->getControllerRequest()->getPage();
                    $page = $page > 0 ? $page : 1;
                    $pagination->setActive($page - 1);
                }
                $this->getView()->addComponent($pagination);
            }
        }
        return $overview;
    }

    protected function initDetailTemplate()
    {
        $this->getView()->setHeading($this->translate('detail.title'));
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
        $this->getView()->setHeading($this->translate('create.title'));
        $edit = new Edit();
        $this->addEditFields($edit);
        $edit->addSubmit(ControllerRequest::SUBMIT_MODE_CREATE, $this->translate('create.submit'), $redirect);
        $edit->addCancel($redirect, $this->translate('create.cancel'), true);
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->addComponent($edit);
        return $edit;
    }

    protected function initEditTemplate(string $redirect = null)
    {
        if (null === $redirect) {
            $redirect = $this->getIndexRedirectLink();
        }
        $this->getView()->setHeading($this->translate('edit.title'));
        $edit = new Edit();
        $this->addEditFields($edit);
        $edit->addSubmit(ControllerRequest::SUBMIT_MODE_SAVE, $this->translate('edit.submit'), $redirect);
        $edit->addCancel($redirect, $this->translate('edit.cancel'), true);
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->addComponent($edit);
        return $edit;
    }


    public function initDeleteTemplate(string $redirect = null)
    {
        if (null === $redirect) {
            $redirect = $this->getIndexRedirectLink();
        }
        $this->getView()->setHeading($this->translate('delete.title'));
        $alert = new Alert();
        $alert->addText("", "")->setValue($this->translate('delete.message'));
        $alert->setBean(new ComponentDataBean());
        $this->getView()->addComponent($alert);
        $edit = new Edit();
        $edit->addSubmit(ControllerRequest::SUBMIT_MODE_DELETE, $this->translate('delete.submit'), $redirect);
        $edit->addCancel($redirect, $this->translate('delete.cancel'), true);
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
