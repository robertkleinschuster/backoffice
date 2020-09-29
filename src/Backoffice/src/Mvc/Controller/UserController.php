<?php


namespace Backoffice\Mvc\Controller;

use Backoffice\Mvc\Model\UserModel;
use Mezzio\Mvc\Controller\ControllerRequest;
use Mezzio\Mvc\View\ComponentDataBean;
use Mezzio\Mvc\View\ComponentModel;
use Mezzio\Mvc\View\Components\Alert\Alert;
use Mezzio\Mvc\View\Components\Detail\Detail;
use Mezzio\Mvc\View\Components\Edit\Edit;
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
        ->setChapter('actions')->setWidth(80);
        $overview->addLink('', '')
            ->setAction($path->setAction('edit')->getPath(false))
            ->setValue("<i class=\"fas fa-edit\"></i>")
        ->setChapter('actions');
        $overview->addLink('', '')
            ->setAction($path->setAction('delete')->getPath(false))
            ->setValue("<i class=\"fas fa-eraser\"></i>")
        ->setChapter('actions');
        $overview->addBadge('Status', 'User_Active')
            ->setValue('Aktiv')
            ->setStyle(Badge::STYLE_SUCCESS)->setWidth(50);
        $overview->addLink('Benutzername', 'User_Username')
            ->setAction($path->setAction('detail')->getPath(false));
        $overview->addText('Vorname', 'Person_Firstname');
        $overview->addText('Nachname', 'Person_Lastname');
        $overview->addText('Anzeigename', 'User_Displayname');

        $overview->getComponentModel()->setComponentDataBeanList($this->getModel()->getFinder()->getBeanList());
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
        $detail->getComponentModel()->setComponentDataBean($this->getModel()->getFinder()->getBean());
        $this->getView()->addComponent($detail);
    }

    public function createAction()
    {
        $this->getView()->getViewModel()->setTitle('Benutzer erstellen');
        $componentModel = new ComponentModel();
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $componentModel->setComponentDataBean($bean);
        $componentModel->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit = $this->addEditUserFields($componentModel);
        $edit->addSubmit('Erstellen', 'save');
        $edit->addLink('Abbrechen', 'cancel')
            ->setAction($this->getPathHelper()->setAction('overview')->getPath())
            ->setAppendToColumnPrevious(true)
            ->setStyle(Link::STYLE_SECONDARY)->setValue('Abbrechen');
        $edit->addAttribute(ControllerRequest::ATTRIBUTE_REDIRECT, $this->getPathHelper()->setAction('overview')->getPath());
        $edit->addAttribute(ControllerRequest::ATTRIBUTE_CREATE, 'create');
        $this->getView()->addComponent($edit);
        foreach ($edit->getFieldList() as $item) {
            $bean->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey()));
        }
        $bean->setFromArray($this->getPreviousAttributes());
        $bean->setData('User_Password', '');
    }

    public function editAction()
    {
        $this->getView()->getViewModel()->setTitle('Benutzer bearbeiten');
        $edit = $this->addEditUserFields(new ComponentModel());
        $edit->addSubmit('Speichern', 'save');
        $edit->addLink('Abbrechen', 'cancel')->setAction($this->getPathHelper()->setAction('overview')->getPath())
            ->setAppendToColumnPrevious(true)
            ->setStyle(Link::STYLE_SECONDARY)->setValue('Abbrechen');
        $edit->addAttribute(ControllerRequest::ATTRIBUTE_REDIRECT, $this->getPathHelper()->setAction('overview')->getPath());
        $bean = $this->getModel()->getFinder()->getBean();
        $bean->setData('User_Password', '');
        $edit->getComponentModel()->setComponentDataBean($bean);
        $edit->getComponentModel()->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->addComponent($edit);
    }

    public function deleteAction() {
        $this->getView()->getViewModel()->setTitle('Benutzer löschen');
        $bean = $this->getModel()->getFinder()->getBean();
        $alert = new Alert('Sicherheitsabfrage', new ComponentModel());
        $alert->getComponentModel()->setComponentDataBean($bean);
        $alert->addText("", "")->setValue('Sind sie sicher, dass sie den Benutzer "{User_Username}" löschen wollen?');
        $this->getView()->addComponent($alert);
        $edit = new Edit('', new ComponentModel());
        $edit->getComponentModel()->setComponentDataBean($bean);
        $edit->addSubmit('Löschen', 'delete');
        $edit->addLink('Abbrechen', 'cancel')->setAction($this->getPathHelper()->setAction('overview')->getPath())
            ->setAppendToColumnPrevious(true)
            ->setStyle(Link::STYLE_SECONDARY)->setValue('Abbrechen');
        $edit->addAttribute(ControllerRequest::ATTRIBUTE_REDIRECT, $this->getPathHelper()->setAction('overview')->getPath());
        $this->getView()->addComponent($edit);
    }

    protected function addEditUserFields($model) {
        $edit = new Edit('Benutzer Daten', $model);
        $edit->setCols(2);
        $edit->addText('Vorname', 'Person_Firstname')
            ->setChapter('Persönliche Daten')
            ->setAutocomplete(Text::AUTOCOMPLETE_GIVEN_NAME)
            ->setAppendToColumnPrevious(true);
        $edit->addText('Nachname', 'Person_Lastname')
            ->setChapter('Persönliche Daten')
            ->setAutocomplete(Text::AUTOCOMPLETE_FAMILY_NAME)
            ->setAppendToColumnPrevious(true);
        $edit->addText('Anzeigename', 'User_Displayname')
            ->setChapter('Persönliche Daten')
            ->setAutocomplete(Text::AUTOCOMPLETE_NICKNAME)
            ->setAppendToColumnPrevious(true);
        $edit->addText('Benutzername', 'User_Username')
            ->setChapter('Anmeldedaten')
            ->setAutocomplete(Text::AUTOCOMPLETE_USERNAME);
        $edit->addText('Passwort', 'User_Password')
            ->setType(Text::TYPE_PASSWORD)
            ->setChapter('Anmeldedaten')
            ->setAutocomplete(Text::AUTOCOMPLETE_NEW_PASSWORD)
            ->setAppendToColumnPrevious(true);
        return $edit;
    }
}
