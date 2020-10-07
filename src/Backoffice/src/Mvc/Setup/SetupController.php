<?php
namespace Backoffice\Mvc\Setup;


use Base\Authentication\Bean\UserBean;
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
        try {
           $count =  $this->getModel()->getFinder()->count();
        } catch (\Throwable $ex) {
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
        $edit = $this->initCreateTemplate();
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $edit->getComponentModel()->setComponentDataBean($bean);
        $bean->setData('User_Password', '');
        foreach ($edit->getFieldList() as $item) {
            $bean->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey()));
        }
        $bean->setFromArray($this->getPreviousAttributes());
        $this->getView()->getViewModel()->setTitle('Einrichtung');
    }

    protected function addEditFields(Edit $edit): void
    {
        $edit->setCols(2);
        $edit->addText('Person_Firstname', 'Vorname')
            ->setChapter('Persönliche Daten')
            ->setAutocomplete(Text::AUTOCOMPLETE_GIVEN_NAME)
            ->setAppendToColumnPrevious(true);
        $edit->addText('Person_Lastname', 'Nachname')
            ->setChapter('Persönliche Daten')
            ->setAutocomplete(Text::AUTOCOMPLETE_FAMILY_NAME)
            ->setAppendToColumnPrevious(true);
        $edit->addText('User_Displayname', 'Anzeigename')
            ->setChapter('Persönliche Daten')
            ->setAutocomplete(Text::AUTOCOMPLETE_NICKNAME)
            ->setAppendToColumnPrevious(true);
        $edit->addText('User_Username', 'Benutzername')
            ->setChapter('Anmeldedaten')
            ->setAutocomplete(Text::AUTOCOMPLETE_USERNAME);
        $edit->addText('User_Password', 'Passwort')
            ->setType(Text::TYPE_PASSWORD)
            ->setChapter('Anmeldedaten')
            ->setAutocomplete(Text::AUTOCOMPLETE_NEW_PASSWORD)
            ->setAppendToColumnPrevious(true);
        $edit->addSubmitAttribute('UserState_Code', UserBean::STATE_ACTIVE)
            ->setAppendToColumnPrevious(true);
    }
}
