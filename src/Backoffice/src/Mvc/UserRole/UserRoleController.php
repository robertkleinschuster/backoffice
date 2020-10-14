<?php


namespace Backoffice\Mvc\UserRole;

use Backoffice\Mvc\Base\BaseController;
use Mvc\Helper\PathHelper;
use Mvc\View\Components\Edit\Edit;

class UserRoleController extends BaseController
{
    protected function initView()
    {
        parent::initView();
        $this->setPermissions('userrole.create', 'userrole.edit', 'userrole.delete');
        if (!$this->checkPermission('userrole')) {
            throw new \Exception('Unauthorized');
        }
    }


    public function createAction()
    {
        $edit = $this->initCreateTemplate($this->getRoleDetailRedirectPath()->getPath());
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $edit->setBean($bean);
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addSelect('UserRole_ID', 'Rolle')
            ->setSelectOptions($this->getModel()->getRoleList($this->getUser()->getPermission_List()));
    }

    public function deleteAction()
    {
        $viewId = $this->getControllerRequest()->getViewIdMap();
        unset($viewId['UserRole_ID']);
        $edit = $this->initDeleteTemplate($this->getRoleDetailRedirectPath()->setViewIdMap($viewId)->getPath());
        $edit->setBean($this->getModel()->getFinder()->getBean());
    }

    protected function getRoleDetailRedirectPath(): PathHelper
    {
        return $this->getPathHelper()->setController('user')->setAction('detail')->setViewIdMap($this->getControllerRequest()->getViewIdMap());
    }
}
