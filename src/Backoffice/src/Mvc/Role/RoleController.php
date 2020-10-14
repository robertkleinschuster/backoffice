<?php


namespace Backoffice\Mvc\Role;

use Backoffice\Mvc\Base\BaseController;
use Mvc\Helper\PathHelper;
use Mvc\View\ComponentDataBean;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Overview\Overview;
use Mvc\View\Components\Toolbar\Toolbar;

/**
 * Class UserRoleController
 * @package Backoffice\Mvc\Controller
 */
class RoleController extends BaseController
{
    protected function initView()
    {
        parent::initView();
        $this->setActiveNavigation('role', 'index');
        $this->setPermissions('role.create', 'role.edit', 'role.delete');
        if (!$this->checkPermission('role')) {
            throw new \Exception('Unauthorized');
        }
    }


    public function indexAction()
    {
        $overview = $this->initOverviewTemplate(new RoleBeanFormatter());
        $overview->setBeanList($this->getModel()->getFinder()->getBeanGenerator());
    }

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setViewIdMap(['UserRole_ID' => '{UserRole_ID}']);
    }

    protected function addOverviewFields(Overview $overview): void
    {
        parent::addOverviewFields($overview);
        $overview->addBadge('UserRole_Active', $this->translate('userrole.state'))->setWidth(50);
        $overview->addText('UserRole_Code', $this->translate('userrole.code'));
    }

    public function editAction()
    {
        $edit = $this->initEditTemplate();
        $edit->setBean($this->getModel()->getFinder()->getBean());
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addText('UserRole_Code', $this->translate('userrole.code'));
    }

    public function createAction()
    {
        $edit = $this->initCreateTemplate();
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $edit->setBean($bean);
        foreach ($edit->getFieldList() as $item) {
            $bean->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey()));
        }
        $bean->setData('UserRole_Active', true);
        $bean->setFromArray($this->getPreviousAttributes());
    }

    public function deleteAction()
    {
        $edit = $this->initDeleteTemplate();
        $bean = $this->getModel()->getFinder()->getBean();
        $edit->setBean($bean);
    }

    public function detailAction() {

        $detail = $this->initDetailTemplate();
        $bean = $this->getModel()->getFinder()->getBean();
        $detail->setBean($bean);

        $toolbar = new Toolbar($this->translate('userrole.detail.permission.title'));
        $toolbar->setBean(new ComponentDataBean());
        $toolbar->addButton(
            $this->getPathHelper()
                ->setController('rolepermission')
                ->setAction('create')
                ->setViewIdMap(['UserRole_ID' => $bean->getData('UserRole_ID')])
                ->getPath(),
            $this->translate('userrole.detail.permission.create'))->setPermission('rolepermission.create');
        $this->getView()->addComponent($toolbar);

        $overview = new Overview();
        $overview->addDeleteIcon(
            $this->getPathHelper()
                ->setController('rolepermission')
                ->setAction('delete')
                ->setViewIdMap([
                    'UserRole_ID' => $bean->getData('UserRole_ID'),
                    'UserPermission_Code' => "{UserPermission_Code}"
                ])
                ->getPath()
        )->setWidth(45)->setPermission('rolepermission.delete');
        $overview->addText('UserPermission_Code', $this->translate('userpermission.code'));
        $overview->setBeanList($bean->getData('UserPermission_BeanList'));
        $this->getView()->addComponent($overview);
    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);
        $detail->addText('UserRole_Code', $this->translate('userrole.code'));
    }
}
