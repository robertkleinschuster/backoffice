<?php

namespace Pars\Backoffice\Mvc\User;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Backoffice\Mvc\Base\CrudController;
use Pars\Base\Authentication\User\UserBean;
use Pars\Mvc\Helper\PathHelper;
use Pars\Mvc\Parameter\IdParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Edit\Fields\Text;
use Pars\Mvc\View\Components\Overview\Fields\Badge;
use Pars\Mvc\View\Components\Overview\Overview;

/**
 * Class UserController
 * @package Pars\Backoffice\Mvc\Controller
 * @method UserModel getModel()
 */
class UserController extends CrudController
{
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

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setController('user')->setId((new IdParameter())->addId('Person_ID'));
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

        $this->addSubController('userrole', 'index');
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
        $localeSelect = $edit->addSelect('Locale_Code', $this->translate('user.locale'))
            ->setChapter($this->translate('user.edit.personaldata'))
            ->setAppendToColumnPrevious(true)
            ->setSelectOptions($this->getModel()->getLocale_Options());

        if (!$this->getControllerRequest()->hasId()) {
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
