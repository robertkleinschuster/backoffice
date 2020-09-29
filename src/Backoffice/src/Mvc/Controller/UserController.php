<?php


namespace Backoffice\Mvc\Controller;

use Backoffice\Mvc\Model\UserModel;
use Mezzio\Mvc\View\ComponentDataBean;
use Mezzio\Mvc\View\ComponentModel;
use Mezzio\Mvc\View\Components\Alert\Alert;
use Mezzio\Mvc\View\Components\Detail\Detail;
use Mezzio\Mvc\View\Components\Edit\Edit;
use Mezzio\Mvc\View\Components\Edit\Fields\Button;
use Mezzio\Mvc\View\Components\Edit\Fields\Text;
use Mezzio\Mvc\View\Components\Overview\Fields\Badge;
use Mezzio\Mvc\View\Components\Overview\Overview;
use Mezzio\Mvc\View\Components\Toolbar\Fields\Link;
use Mezzio\Mvc\View\Components\Toolbar\Toolbar;

/**
 * Class UserController
 * @package Backoffice\Mvc\Controller
 * @method UserModel getModel()
 */
class UserController extends BaseController
{

    public function init()
    {
        parent::init();
        $this->getModel()->initUserTable();
        $this->getView()->setLayout('layout/dashboard');
        $this->setActiveNavigation('user', 'overview');
    }

    public function indexAction() {
        $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('overview')->getPath());
    }

    public function overviewAction()
    {
        $this->getView()->getViewModel()->setTitle('Benutzerübersicht');
        $toolbar = new Toolbar('', new ComponentModel());
        $toolbar->getComponentModel()->setComponentDataBean(new ComponentDataBean());
        $toolbar->addLink('Neu', 'create')
            ->setAction($this->getPathHelper()->setAction('create')->getPath())
            ->setValue('Neu')
            ->addOption(Link::OPTION_BUTTON_STYLE)
            ->setStyle(Link::STYLE_SECONDARY);

        $this->getView()->addComponent($toolbar);

        $overview = new Overview('', new ComponentModel());
        $path = $this->getPathHelper()->setViewIdMap(['Person_ID' => '{Person_ID}']);
        $overview->addLink('', '')
            ->setAction($path->setAction('detail')
                ->getPath(false))
            ->setValue("<i class=\"fas fa-search\"></i>")
        ->setChapter('actions')->setWidth(65);
        $overview->addLink('', '')
            ->setAction($path->setAction('edit')->getPath(false))
            ->setValue("<i class=\"fas fa-edit\"></i>")
        ->setChapter('actions');
        $overview->addLink('', '')
            ->setAction($path->setAction('delete')->getPath(false))
            ->setValue("<i class=\"fas fa-eraser\"></i>")
        ->setChapter('actions');
        $overview->addBadge('Status', 'User_Active')->setValue('Aktiv')->setStyle(Badge::STYLE_SUCCESS)->setWidth(50);
        $overview->addLink('Benutzername', 'User_Username')->setAction($path->setAction('detail')->getPath(false));
        $overview->addText('Vorname', 'Person_Firstname');
        $overview->addText('Nachname', 'Person_Lastname');
        $overview->addText('Anzeigename', 'User_Displayname');

        $overview->getComponentModel()->setComponentDataBeanList($this->getModel()->getUserBeanList());
        $this->getView()->addComponent($overview);

    }

    public function detailAction()
    {
        $this->getView()->getViewModel()->setTitle('Details zum Benutzer');

        if (!count($this->getControllerRequest()->getViewIdMap())) {
            $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('overview')->getPath());
            return;
        }
        $detail = new Detail('', new ComponentModel());
        $detail->addText('Benutzername', 'User_Username');
        $detail->addText('Anzeigename', 'User_Displayname');
        $detail->addText('Name', 'Person_Firstname');
        $detail->addText('Nachname', 'Person_Lastname');
        $detail->getComponentModel()->setComponentDataBean(
            $this->getModel()->getUserBean($this->getControllerRequest()->getViewIdMap())
        );
        $this->getView()->addComponent($detail);
    }

    public function createAction()
    {
        $this->getView()->getViewModel()->setTitle('Benutzer erstellen');
        if ($this->getControllerRequest()->hasAttribute('submitUser') && $this->validateToken()) {
            if ($this->getModel()->submitUser($this->getControllerRequest()->getAttributes())) {
                $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('overview')->getPath());
                return;
            }
        }

        $componentModel  = new ComponentModel();

        $bean = $this->getModel()->getUserBean();

        $componentModel->setComponentDataBean($bean);
        $componentModel->getValidationHelper()->addErrorFieldMap($this->getModel()->getValidationHelper()->getErrorFieldMap());

        $edit = new Edit('Benutzer Daten', $componentModel);
        $edit->addText('Vorname', 'Person_Firstname')->setChapter('Persönliche Daten')->setAutocomplete(Text::AUTOCOMPLETE_GIVEN_NAME);
        $edit->addText('Nachname', 'Person_Lastname')->setChapter('Persönliche Daten')->setAutocomplete(Text::AUTOCOMPLETE_FAMILY_NAME);
        $edit->addText('Anzeigename', 'User_Displayname')->setChapter('Persönliche Daten')->setAutocomplete(Text::AUTOCOMPLETE_NICKNAME);
        $edit->addText('Benutzername', 'User_Username')->setChapter('Anmeldedaten')->setAutocomplete(Text::AUTOCOMPLETE_USERNAME);
        $edit->addText('Passwort', 'User_Password')->setType(Text::TYPE_PASSWORD)->setChapter('Anmeldedaten')->setAutocomplete(Text::AUTOCOMPLETE_NEW_PASSWORD);
        $edit->addButton('Erstellen', 'submitUser')->setType(Button::TYPE_SUBMIT);
        $this->getView()->addComponent($edit);

        foreach ($edit->getFieldList() as $item) {
            $bean->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey()));
        }
        $bean->setData('User_Password', '');
    }

    public function editAction()
    {
        $this->getView()->getViewModel()->setTitle('Benutzer bearbeiten');
        if ($this->getControllerRequest()->hasAttribute('submitUser') && $this->validateToken()) {
            if ($this->getModel()->submitUser($this->getControllerRequest()->getAttributes(), $this->getControllerRequest()->getViewIdMap())) {
                $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('overview')->getPath());
                return;
            }
        }

        $edit = new Edit('Benutzer Daten', new ComponentModel());
        $edit->addText('Vorname', 'Person_Firstname')->setChapter('Persönliche Daten')->setAutocomplete(Text::AUTOCOMPLETE_GIVEN_NAME);
        $edit->addText('Nachname', 'Person_Lastname')->setChapter('Persönliche Daten')->setAutocomplete(Text::AUTOCOMPLETE_FAMILY_NAME);
        $edit->addText('Anzeigename', 'User_Displayname')->setChapter('Persönliche Daten')->setAutocomplete(Text::AUTOCOMPLETE_NICKNAME);
        $edit->addText('Benutzername', 'User_Username')->setChapter('Anmeldedaten')->setAutocomplete(Text::AUTOCOMPLETE_USERNAME);
        $edit->addText('Passwort', 'User_Password')->setType(Text::TYPE_PASSWORD)->setChapter('Anmeldedaten')->setAutocomplete(Text::AUTOCOMPLETE_NEW_PASSWORD);


        $bean = $this->getModel()->getUserBean($this->getControllerRequest()->getViewIdMap());
        $bean->setData('User_Password', '');
        $edit->getComponentModel()->setComponentDataBean($bean);
        $edit->addButton('Speichern', 'submitUser')->setType(Button::TYPE_SUBMIT);
        $this->getView()->addComponent($edit);
    }

    public function deleteAction() {
        $this->getView()->getViewModel()->setTitle('Benutzer löschen');

        if ($this->getControllerRequest()->hasAttribute('deleteUser') && $this->validateToken()) {
            if ($this->getModel()->deleteUser($this->getControllerRequest()->getViewIdMap())) {
                $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('overview')->getPath());
            }
        }

        if ($this->getControllerRequest()->hasAttribute('abort')) {
            $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('overview')->getPath());
        }
        $bean = $this->getModel()->getUserBean($this->getControllerRequest()->getViewIdMap());

        $alert = new Alert('Sicherheitsabfrage', new ComponentModel());
        $alert->getComponentModel()->setComponentDataBean($bean);
        $alert->addText("", "")->setValue('Sind sie sicher, dass sie den Benutzer "{User_Username}" löschen wollen?');
        $this->getView()->addComponent($alert);
        $edit = new Edit('', new ComponentModel());
        $edit->setCols(2);
        $edit->getComponentModel()->setComponentDataBean($bean);
        $edit->addButton('Abbrechen', 'abort')->setType(Button::TYPE_SUBMIT);
        $edit->addButton('Löschen', 'deleteUser')->setStyle(Button::STYLE_DANGER)->setType(Button::TYPE_SUBMIT);
        $this->getView()->addComponent($edit);
    }

}
