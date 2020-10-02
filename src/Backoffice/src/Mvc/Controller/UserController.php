<?php


namespace Backoffice\Mvc\Controller;

use Backoffice\Authentication\Bean\UserBean;
use Backoffice\Mvc\Model\UserModel;
use Backoffice\Mvc\Model\UserRoleModel;
use Mezzio\Mvc\Controller\ControllerRequest;
use Mezzio\Mvc\View\ComponentDataBean;
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
    protected function initView()
    {
        parent::initView();
        $this->setActiveNavigation('user', 'overview');
    }


    public function indexAction() {
        $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('overview')->getPath());
    }

    public function overviewAction()
    {
        $this->getView()->getViewModel()->setTitle('Benutzer Übersicht');
        $toolbar = new Toolbar();
        $toolbar->getComponentModel()->setComponentDataBean(new ComponentDataBean());
        $toolbar->addButton($this->getPathHelper()->setAction('create')->getPath(), 'Neu');
        $this->getView()->addComponent($toolbar);
        $overview = new Overview();
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
        $overview->addBadge('User_Active', 'Status')
            ->setValue('Aktiv')
            ->setStyle(Badge::STYLE_SUCCESS)->setWidth(50);

        $overview->addText('User_Username', 'Benutzername');
        $overview->addText('Person_Firstname', 'Vorname');
        $overview->addText('Person_Lastname', 'Nachname');
        $overview->addText('User_Displayname', 'Anzeigename');

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

        $detail = new Detail();
        $detail->addText( 'User_Username', 'Benutzername');
        $detail->addText('User_Displayname', 'Anzeigename');
        $detail->addText('Person_Firstname', 'Name');
        $detail->addText('Person_Lastname', 'Nachname');
        $bean = $this->getModel()->getFinder()->getBean();
        $detail->getComponentModel()->setComponentDataBean($bean);
        $this->getView()->addComponent($detail);

        $toolbar = new Toolbar('Rollen');
        $toolbar->getComponentModel()->addComponentDataBean(new ComponentDataBean());
        $toolbar->addButton(
            $this->getPathHelper()
                ->setController('userrole')
                ->setAction('linktouser')
                ->setParams(['Person_ID' => $bean->getData('Person_ID')])
                ->getPath(),
            'Hinzufügen'
        );
        $this->getView()->addComponent($toolbar);

        $overview = new Overview();
        $overview->getComponentModel()->setComponentDataBeanList($bean->getData('UserRole_BeanList'));
        $overview->addText('UserRole_Code', 'Code');
        $this->getView()->addComponent($overview);
    }

    public function createAction()
    {
        $this->getView()->getViewModel()->setTitle('Benutzer erstellen');
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $edit = $this->addEditUserFields();
        $edit->getComponentModel()->setComponentDataBean($bean);
        $edit->getComponentModel()->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->addSubmit('save', 'Erstellen');
        $edit->addLink('cancel', 'Abbrechen')
            ->setAction($this->getPathHelper()->setAction('overview')->getPath())
            ->setAppendToColumnPrevious(true)
            ->setStyle(Link::STYLE_SECONDARY)->setValue('Abbrechen');
        $edit->addSubmitAttribute(ControllerRequest::ATTRIBUTE_REDIRECT, $this->getPathHelper()->setAction('overview')->getPath());
        $edit->addSubmitAttribute(ControllerRequest::ATTRIBUTE_CREATE, 'create');
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
        $edit = $this->addEditUserFields();
        $edit->addSubmit('save', 'Speichern');
        $edit->addLink('cancel', 'Abbrechen')->setAction($this->getPathHelper()->setAction('overview')->getPath())
            ->setAppendToColumnPrevious(true)
            ->setStyle(Link::STYLE_SECONDARY)->setValue('Abbrechen');
        $edit->addSubmitAttribute(ControllerRequest::ATTRIBUTE_REDIRECT, $this->getPathHelper()->setAction('overview')->getPath());
        $bean = $this->getModel()->getFinder()->getBean();
        $bean->setData('User_Password', '');
        $edit->getComponentModel()->setComponentDataBean($bean);
        $edit->getComponentModel()->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->addComponent($edit);
    }

    public function deleteAction() {
        $this->getView()->getViewModel()->setTitle('Benutzer löschen');
        $bean = $this->getModel()->getFinder()->getBean();
        $alert = new Alert();
        $alert->getComponentModel()->setComponentDataBean($bean);
        $alert->addText("", "")->setValue('Sind sie sicher, dass sie den Benutzer "{User_Username}" löschen wollen?');
        $this->getView()->addComponent($alert);
        $edit = new Edit();
        $edit->getComponentModel()->setComponentDataBean($bean);
        $edit->addSubmit('delete', 'Löschen');
        $edit->addLink('cancel', 'Abbrechen')->setAction($this->getPathHelper()->setAction('overview')->getPath())
            ->setAppendToColumnPrevious(true)
            ->setStyle(Link::STYLE_SECONDARY)->setValue('Abbrechen');
        $edit->addSubmitAttribute(ControllerRequest::ATTRIBUTE_REDIRECT, $this->getPathHelper()->setAction('overview')->getPath());
        $this->getView()->addComponent($edit);
    }

    protected function addEditUserFields() {
        $edit = new Edit('Benutzer Daten');
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
        return $edit;
    }
}
