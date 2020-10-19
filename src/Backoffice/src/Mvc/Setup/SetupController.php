<?php
namespace Backoffice\Mvc\Setup;


use Base\Authentication\User\UserBean;
use Base\Database\DatabaseMiddleware;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Edit\Fields\Text;

class SetupController extends \Backoffice\Mvc\Base\BaseController
{
    protected function initView()
    {
        parent::initView();
        $this->getView()->setLayout('layout/default');
    }

    public function init()
    {
        $this->initView();
        $this->initModel();
        $this->handleSubmit();
    }


    protected function initModel()
    {
        $this->getModel()->setDbAdapter($this->getControllerRequest()->getServerRequest()->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE));
        $this->getModel()->init();
        $metadata = \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($this->getModel()->getDbAdpater());
        $tableNames = $metadata->getTableNames($this->getModel()->getDbAdpater()->getCurrentSchema());
        if (in_array('Person', $tableNames) && in_array('User', $tableNames)) {
            $count = $this->getModel()->getFinder()->count();
        } else {
            $count = 0;
        }
        if ($count > 0) {
            $this->getControllerResponse()->setRedirect($this->getPathHelper()->setController('index')->setAction('index')->getPath());
        } else {
            $this->getModel()->addOption(SetupModel::OPTION_CREATE_ALLOWED);
        }
    }


    public function indexAction()
    {
        $this->getView()->setHeading($this->translate('setup.title'));
        $edit = $this->initCreateTemplate();
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $edit->setBean($bean);
        $bean->setData('User_Password', '');
        foreach ($edit->getFieldList() as $item) {
            $bean->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey()));
        }
        $bean->setFromArray($this->getPreviousAttributes());
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
        $edit->addText('User_Username', $this->translate('user.username'))
            ->setChapter($this->translate('user.edit.signindata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_USERNAME);
        $edit->addText('User_Password', $this->translate('user.password'))
            ->setType(Text::TYPE_PASSWORD)
            ->setChapter($this->translate('user.edit.signindata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_NEW_PASSWORD)
            ->setAppendToColumnPrevious(true);
        $edit->addSubmitAttribute('UserState_Code', UserBean::STATE_ACTIVE)
            ->setAppendToColumnPrevious(true);
    }
}
