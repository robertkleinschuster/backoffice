<?php


namespace Backoffice\Mvc\User;

use Base\Authentication\Bean\UserBean;
use Backoffice\Mvc\Base\BaseController;
use Backoffice\Mvc\Role\RoleBeanFormatter;
use Mvc\Helper\PathHelper;
use Mvc\View\ComponentDataBean;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Edit\Fields\Text;
use Mvc\View\Components\Overview\Fields\Badge;
use Mvc\View\Components\Overview\Overview;
use Mvc\View\Components\Toolbar\Toolbar;

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
        $this->setActiveNavigation('user', 'index');
        $this->setPermissions('user.create', 'user.edit', 'user.delete');
        if (!$this->checkPermission('user')) {
            throw new \Exception('Unauthorized');
        }
    }


    public function indexAction()
    {
        $overview = $this->initOverviewTemplate(new RoleBeanFormatter());
        $overview->getComponentModel()->setComponentDataBeanList($this->getModel()->getFinder()->getBeanGenerator());
    }

    public function detailAction()
    {
        $detail = $this->initDetailTemplate();

        $bean = $this->getModel()->getFinder()->getBean();
        $detail->getComponentModel()->setComponentDataBean($bean);
        $toolbar = new Toolbar('Rollen');
        $toolbar->getComponentModel()->addComponentDataBean(new ComponentDataBean());
        $toolbar->addButton(
            $this->getPathHelper()
                ->setController('userrole')
                ->setAction('create')
                ->setViewIdMap(['Person_ID' => $bean->getData('Person_ID')])
                ->getPath(),
            'Hinzufügen'
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
        $overview->addText('UserRole_Code', 'Code');
        $overview->getComponentModel()->setComponentDataBeanList($bean->getData('UserRole_BeanList'));
        $this->getView()->addComponent($overview);
    }

    public function createAction()
    {
        $edit = $this->initCreateTemplate();
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $edit->getComponentModel()->setComponentDataBean($bean);
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
        $edit->getComponentModel()->setComponentDataBean($bean);
        $bean->setData('User_Password', '');
    }

    public function deleteAction()
    {
        $edit = $this->initDeleteTemplate();
        $edit->getComponentModel()->setComponentDataBean($this->getModel()->getFinder()->getBean());

    }


    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setViewIdMap(['Person_ID' => '{Person_ID}']);
    }

    protected function addOverviewFields(Overview $overview): void
    {
        $overview->addBadge('User_Active', 'Status')
            ->setValue('Aktiv')
            ->setStyle(Badge::STYLE_SUCCESS)->setWidth(50);
        $overview->addText('User_Username', 'Benutzername');
        $overview->addText('Person_Firstname', 'Vorname');
        $overview->addText('Person_Lastname', 'Nachname');
        $overview->addText('User_Displayname', 'Anzeigename');
    }

    protected function addDetailFields(Detail $detail): void
    {
        $detail->addText('User_Username', 'Benutzername');
        $detail->addText('User_Displayname', 'Anzeigename');
        $detail->addText('Person_Firstname', 'Name');
        $detail->addText('Person_Lastname', 'Nachname');
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
