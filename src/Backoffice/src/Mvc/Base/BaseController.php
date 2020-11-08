<?php

namespace Pars\Backoffice\Mvc\Base;

use Pars\Base\Authentication\User\UserBean;
use Pars\Base\Database\DatabaseMiddleware;
use Pars\Base\Logging\LoggingMiddleware;
use Pars\Base\Translation\TranslatorMiddleware;
use Laminas\Db\Adapter\Profiler\ProfilerInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Csrf\CsrfGuardInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessagesInterface;
use Mezzio\Session\LazySession;
use Mezzio\Session\SessionMiddleware;
use Niceshops\Core\Attribute\AttributeAwareInterface;
use Niceshops\Core\Attribute\AttributeAwareTrait;
use Niceshops\Core\Option\OptionAwareInterface;
use Niceshops\Core\Option\OptionAwareTrait;
use Pars\Mvc\Controller\AbstractController;
use Pars\Mvc\Controller\ControllerResponse;
use Pars\Mvc\Helper\ValidationHelper;
use Pars\Mvc\Parameter\NavParameter;
use Pars\Mvc\View\Components\Alert\Alert;
use Pars\Mvc\View\Components\Toolbar\Toolbar;
use Pars\Mvc\View\Navigation\Element;
use Pars\Mvc\View\Navigation\Navigation;
use Pars\Mvc\View\View;

/**
 * Class BaseController
 * @package Pars\Backoffice\Mvc\Controller
 * @method BaseModel getModel()
 */
abstract class BaseController extends AbstractController implements AttributeAwareInterface, OptionAwareInterface
{
    use AttributeAwareTrait;
    use OptionAwareTrait;

    public const ATTRIBUTE_CREATE_PERMISSION = 'create_permission';
    public const ATTRIBUTE_EDIT_PERMISSION = 'edit_permission';
    public const ATTRIBUTE_DELETE_PERMISSION = 'delete_permission';

    /**
     * @param string $create
     * @param string $edit
     * @param string $delete
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     */
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
     * @return TranslatorInterface
     */
    protected function getTranslator(): TranslatorInterface
    {
        return $this->getControllerRequest()
            ->getServerRequest()
            ->getAttribute(TranslatorMiddleware::TRANSLATOR_ATTRIBUTE);
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
        $this->getSession()->set('lastnav', $id);
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

    /**
     * @param string $name
     * @param $token
     * @return bool
     */
    protected function validateToken(string $name, $token)
    {
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

    /**
     * @return array
     */
    protected function getValidationErrorMap(): array
    {
        return $this->getFlashMessanger()->getFlash('validationErrorMap', []);
    }

    /**
     * @return array
     */
    protected function getPreviousAttributes(): array
    {
        return $this->getFlashMessanger()->getFlash('previousAttributes', []);
    }

    /**
     * @return LazySession
     */
    protected function getSession(): LazySession
    {
        return $this->getControllerRequest()->getServerRequest()->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
    }

    /**
     * @return UserBean
     */
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

    /**
     * @return mixed|void
     * @throws \Niceshops\Bean\Type\Base\BeanException
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     */
    protected function initView()
    {
        $this->setView(new View('layout/dashboard'));
        $this->getView()->setTitle('Backoffice');
        $this->getView()->setData('favicon', '/backoffice.ico');
        $this->getView()->setBeanConverter(new BackofficeBeanConverter());
        $this->getView()->setPermissionList($this->getUser()->getPermission_List());

        $navigation = new Navigation($this->translate('navigation.content'));
        $navigation->setPermissionList($this->getUser()->getPermission_List());

        $element = new Element(
            $this->translate('navigation.content.cmsmenu'),
            $this->getPathHelper()
                ->setController('cmsmenu')
                ->setAction('index')
                ->addParameter((new NavParameter())->setId($navigation->getId())->setIndex(0))
                ->getPath()
        );
        $element->setPermission('cmsmenu');
        $navigation->addElement($element);

        $element = new Element(
            $this->translate('navigation.content.cmssite'),
            $this->getPathHelper()
                ->setController('cmssite')
                ->setAction('index')
                ->addParameter((new NavParameter())->setId($navigation->getId())->setIndex(1))
                ->getPath()
        );
        $element->setPermission('cmssite');
        $navigation->addElement($element);

        $element = new Element(
            $this->translate('navigation.content.cmsparagraph'),
            $this->getPathHelper()
                ->setController('cmsparagraph')
                ->setAction('index')
                ->addParameter((new NavParameter())->setId($navigation->getId())->setIndex(2))
                ->getPath()
        );
        $element->setPermission('cmsparagraph');
        $navigation->addElement($element);


        $this->getView()->addNavigation($navigation);

        $navigation = new Navigation($this->translate('navigation.media'));
        $navigation->setPermissionList($this->getUser()->getPermission_List());

        $element = new Element(
            $this->translate('navigation.file.file'),
            $this->getPathHelper()
                ->setController('file')
                ->setAction('index')
                ->addParameter((new NavParameter())->setId($navigation->getId())->setIndex(0))
                ->getPath()
        );
        $element->setPermission('file');
        $navigation->addElement($element);

        $element = new Element(
            $this->translate('navigation.file.directory'),
            $this->getPathHelper()
                ->setController('filedirectory')
                ->setAction('index')
                ->addParameter((new NavParameter())->setId($navigation->getId())->setIndex(1))
                ->getPath()
        );
        $element->setPermission('filedirectory');
        $navigation->addElement($element);

        $this->getView()->addNavigation($navigation);

        $navigation = new Navigation($this->translate('navigation.system'));
        $navigation->setPermissionList($this->getUser()->getPermission_List());

        $element = new Element(
            $this->translate('navigation.system.translation'),
            $this->getPathHelper()
                ->setController('translation')
                ->setAction('index')
                ->addParameter((new NavParameter())->setId($navigation->getId())->setIndex(0))
                ->getPath()
        );
        $element->setPermission('translation');
        $navigation->addElement($element);


        $element = new Element(
            $this->translate('navigation.system.locale'),
            $this->getPathHelper()
                ->setController('locale')
                ->setAction('index')
                ->addParameter((new NavParameter())->setId($navigation->getId())->setIndex(1))
                ->getPath()
        );
        $element->setPermission('locale');
        $navigation->addElement($element);

        $element = new Element(
            $this->translate('navigation.system.user'),
            $this->getPathHelper()
                ->setController('user')
                ->setAction('index')
                ->addParameter((new NavParameter())->setId($navigation->getId())->setIndex(2))
                ->getPath()
        );
        $element->setPermission('user');
        $navigation->addElement($element);
        $element = new Element(
            $this->translate('navigation.system.role'),
            $this->getPathHelper()
                ->setController('role')
                ->setAction('index')
                ->addParameter((new NavParameter())->setId($navigation->getId())->setIndex(3))
                ->getPath()
        );
        $element->setPermission('role');
        $navigation->addElement($element);


        $element = new Element(
            $this->translate('navigation.system.update'),
            $this->getPathHelper()
                ->setController('update')
                ->setAction('index')
                ->getPath()
        );
        $element->setPermission('update');
        #   $navigation->addElement($element);

        $this->getView()->addNavigation($navigation);

        $navigation = new Navigation($this->translate('navigation.account'), Navigation::POSITION_HEADER);
        $navigation->setPermissionList($this->getUser()->getPermission_List());
        $element = new Element(
            $this->translate('navigation.account.logout'),
            $this->getPathHelper()
                ->setController('auth')
                ->setAction('logout')
                ->getPath()
        );
        $navigation->addElement($element);
        $this->getView()->addNavigation($navigation);

        $this->getView()->setToolbar(new Toolbar());
    }

    /**
     * @return mixed|void
     */
    protected function initModel()
    {
        $this->getModel()->setDbAdapter(
            $this->getControllerRequest()->getServerRequest()->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE)
        );
        $this->getModel()->setUser($this->getUser());
        $this->getModel()->setTranslator($this->getTranslator());
        $this->getModel()->setBeanConverter(new BackofficeBeanConverter());
        $this->getModel()->initialize();
    }


    /**
     * @return mixed|void
     * @throws \Niceshops\Bean\Type\Base\BeanException
     */
    public function finalize()
    {
        if ($this->hasView()) {
            $navList = $this->getView()->getNavigationList();
            foreach ($navList as $nav) {
                if ($nav->getId() == $this->getSession()->get('lastnav')) {
                    $nav->setActive($this->getNavigationState($nav->getId()));
                }
            }
        }
        $this->getView()->setData('token', $this->generateToken('submit_token'));
        $validationHelper = new ValidationHelper();
        $validationHelper->addErrorFieldMap($this->getValidationErrorMap());
        if (count($validationHelper->getErrorList('Permission'))) {
            $alert = new Alert();
            foreach ($validationHelper->getErrorList('Permission') as $item) {
                $alert->addText('', '')->setValue($item);
            }
            $this->getView()->addComponent($alert, true);
        }

        $profiler = $this->getModel()->getDbAdpater()->getProfiler();
        if ($profiler instanceof ProfilerInterface && $this->getSession()->get('debug', false)) {
            $profiles = $profiler->getProfiles();
            $alert = new Alert();
            $alert->setHeading('Debug');
            $alert->setStyle(Alert::STYLE_WARNING);
            $alert->addText('queryCount', '')
                ->setValue(
                    'Abfragen: '
                    . count($profiles)
                    . '<br>'
                    . array_sum(array_column($profiles, 'elapse'))
                    . ' ms'
                );
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
        $this->getControllerRequest()->
        getServerRequest()
            ->getAttribute(LoggingMiddleware::LOGGER_ATTRIBUTE)->error("Error: ", ['exception' => $exception]);

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

    public function clearcacheAction()
    {
        $result = 'Clear Cache';
        $result .= "<br>Backoffice {$this->getTranslator()->getLocale()} Translation: "
            . $this->getTranslator()->clearCache('backoffice', $this->getTranslator()->getLocale());
        $this->getControllerResponse()->removeOption(ControllerResponse::OPTION_RENDER_RESPONSE);
        $result .= '<br>Done';
        $this->getControllerResponse()->setBody($result);
    }
}
