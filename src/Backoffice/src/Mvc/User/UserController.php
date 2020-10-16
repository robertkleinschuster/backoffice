<?php


namespace Backoffice\Mvc\User;

use Backoffice\Mvc\Base\BaseController;
use Backoffice\Mvc\Role\RoleBeanFormatter;
use Base\Authentication\User\UserBean;
use Base\Localization\LocalizationMiddleware;
use Mvc\Helper\PathHelper;
use Mvc\View\ComponentDataBean;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Edit\Fields\Text;
use Mvc\View\Components\Overview\Fields\Badge;
use Mvc\View\Components\Overview\Overview;
use Mvc\View\Components\Toolbar\Toolbar;
use NiceshopsDev\Bean\BeanInterface;

/**
 * Class UserController
 * @package Backoffice\Mvc\Controller
 * @method UserModel getModel()
 */
class UserController extends BaseController
{
    protected function initView()
    {
        parent::initView();
    }

    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('user.create', 'user.edit', 'user.delete');
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('user');
    }


    public function indexAction()
    {
        $overview = $this->initOverviewTemplate(new RoleBeanFormatter());
        $overview->setBeanList($this->getModel()->getFinder()->getBeanGenerator());
    }

    public function detailAction()
    {
        $detail = $this->initDetailTemplate();

        $bean = $this->getModel()->getFinder()->getBean();
        $detail->setBean($bean);
        $toolbar = new Toolbar($this->translate('user.detail.role.title'));
        $toolbar->setBean(new ComponentDataBean());
        $toolbar->addButton(
            $this->getPathHelper()
                ->setController('userrole')
                ->setAction('create')
                ->setViewIdMap(['Person_ID' => $bean->getData('Person_ID')])
                ->getPath(),
            $this->translate('user.detail.role.create')
        )->setPermission('userrole.create');
        $this->getView()->addComponent($toolbar);

        $overview = new Overview();
        $overview->addDeleteIcon(
            $this->getPathHelper()
                ->setController('userrole')
                ->setAction('delete')
                ->setViewIdMap([
                    'Person_ID' => $bean->getData('Person_ID'),
                    'UserRole_ID' => "{UserRole_ID}"
                ])
                ->getPath()
        )->setWidth(45)->setPermission('userrole.delete');
        $overview->addText('UserRole_Code', $this->translate('userrole.code'));
        $overview->setBeanList($bean->getData('UserRole_BeanList'));
        $this->getView()->addComponent($overview);
    }

    public function createAction()
    {
        $edit = $this->initCreateTemplate();
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $edit->setBean($bean);
        $bean->setData('User_Password', '');
        foreach ($edit->getFieldList() as $item) {
            $bean->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey()));
        }
        $bean->setFromArray($this->getPreviousAttributes());
    }

    public function editAction()
    {
        $edit = $this->initEditTemplate();
        $bean = $this->getModel()->getFinder()->getBean();
        $edit->setBean($bean);
        $bean->setData('User_Password', '');
    }

    public function edit_meAction()
    {
        $edit = $this->initEditTemplate($this->getPathHelper()->setController('index')->setAction('index')->getPath());
        $bean = $this->getModel()->getFinder()->getBean();
        $edit->setBean($bean);
        $bean->setData('User_Password', '');
        $this->getView()->setHeading($this->translate('user.edit_me.title'));

    }

    public function deleteAction()
    {
        $edit = $this->initDeleteTemplate();
        $edit->setBean($this->getModel()->getFinder()->getBean());

    }


    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setViewIdMap(['Person_ID' => '{Person_ID}']);
    }

    protected function addOverviewFields(Overview $overview): void
    {
        $overview->addBadge('UserState_Code', $this->translate('userstate.code'))
            ->setWidth(50)
            ->setFormat(function (BeanInterface $bean, Badge $badge) {
                switch ($bean->getData('UserState_Code')) {
                    case UserBean::STATE_ACTIVE:
                        $badge->setStyle(Badge::STYLE_SUCCESS);
                        return $this->translate('userstate.code.active');
                    case UserBean::STATE_INACTIVE:
                        $badge->setStyle(Badge::STYLE_WARNING);
                        return $this->translate('userstate.code.inactive');
                    case UserBean::STATE_LOCKED:
                        $badge->setStyle(Badge::STYLE_DANGER);
                        return $this->translate('userstate.code.locked');
                }
                return $bean->getData('UserState_Code');
            });
        $overview->addText('User_Username', $this->translate('user.username'));
        $overview->addText('Person_Firstname', $this->translate('person.firstname'));
        $overview->addText('Person_Lastname', $this->translate('person.lastname'));
        $overview->addText('User_Displayname', $this->translate('user.displayname'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        $detail->addText('User_Username', $this->translate('user.username'));
        $detail->addText('User_Displayname', $this->translate('user.displayname'));
        $detail->addText('Person_Firstname', $this->translate('person.firstname'));
        $detail->addText('Person_Lastname', $this->translate('person.lastname'));
    }

    protected function addEditFields(Edit $edit): void
    {
        $edit->setCols(2);

        $edit->addText('Person_Firstname', $this->translate('person.firstname'))
            ->setChapter($this->translate('user.edit.personaldata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_GIVEN_NAME)
            ->setAppendToColumnPrevious(true);
        $edit->addText('Person_Lastname', $this->translate('person.lastname'))
            ->setChapter($this->translate('user.edit.personaldata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_FAMILY_NAME)
            ->setAppendToColumnPrevious(true);
        $edit->addText('User_Displayname', $this->translate('user.displayname'))
            ->setChapter($this->translate('user.edit.personaldata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_NICKNAME)
            ->setAppendToColumnPrevious(true);
        $localeSelect = $edit->addSelect('User_Locale', $this->translate('user.locale'))
            ->setChapter($this->translate('user.edit.personaldata'))
            ->setAppendToColumnPrevious(true)
            ->setSelectOptions(LocalizationMiddleware::getLocaleList());

        if (!$this->getControllerRequest()->hasViewIdMap()) {
            $localeSelect->setValue($this->getTranslator()->getLocale());
        }

        $edit->addText('User_Username', $this->translate('user.username'))
            ->setChapter($this->translate('user.edit.signindata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_USERNAME);
        $edit->addText('User_Password', $this->translate('user.password'))
            ->setType(Text::TYPE_PASSWORD)
            ->setChapter($this->translate('user.edit.signindata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_NEW_PASSWORD)
            ->setAppendToColumnPrevious(true);
        $edit->addSelect('UserState_Code', $this->translate('userstate.code'))
            ->setChapter($this->translate('user.edit.signindata'))
            ->setSelectOptions($this->getModel()->getUserState_Options())
            ->setAppendToColumnPrevious(true)
            ->setPermission('user.edit.state');
    }
}
