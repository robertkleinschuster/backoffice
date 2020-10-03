<?php


namespace Backoffice\Mvc\UserRole;

use Backoffice\Mvc\Base\BaseController;
use Mezzio\Mvc\Helper\PathHelper;
use Mezzio\Mvc\View\Components\Edit\Edit;

class UserRoleController extends BaseController
{
    protected function initView()
    {
        parent::initView();
        $this->setPermissions('userrole.create', 'userrole.edit', 'userrole.delete');
    }


    public function createAction()
    {
        $this->getView()->getViewModel()->setTitle('Hinzufügen');
        $edit = $this->initCreateTemplate($this->getRoleDetailRedirectPath()->getPath());
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $edit->getComponentModel()->setComponentDataBean($bean);
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addSelect('UserRole_ID', 'Rolle')
            ->setSelectOptions($this->getModel()->getRoleList());
    }

    public function deleteAction()
    {
        $viewId = $this->getControllerRequest()->getViewIdMap();
        unset($viewId['UserRole_ID']);
        $edit = $this->initDeleteTemplate($this->getRoleDetailRedirectPath()->setViewIdMap($viewId)->getPath());
        $edit->getComponentModel()->setComponentDataBean($this->getModel()->getFinder()->getBean());
    }

    protected function getRoleDetailRedirectPath(): PathHelper
    {
        return $this->getPathHelper()->setController('user')->setAction('detail')->setViewIdMap($this->getControllerRequest()->getViewIdMap());
    }
}
