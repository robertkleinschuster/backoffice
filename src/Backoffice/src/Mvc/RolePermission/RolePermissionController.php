<?php


namespace Backoffice\Mvc\RolePermission;


use Backoffice\Mvc\Base\BaseController;
use Mvc\Helper\PathHelper;
use Mvc\View\Components\Edit\Edit;

/**
 * Class RolePermissionController
 * @package Backoffice\Mvc\RolePermission
 * @method RolePermissionModel getModel()
 */
class RolePermissionController extends BaseController
{
    protected function initView()
    {
        parent::initView();
        $this->setPermissions('rolepermission.create', 'rolepermission.edit', 'rolepermission.delete');
        if (!$this->checkPermission('rolepermission')) {
            throw new \Exception('Unauthorized');
        }
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
        $edit->addSelect('UserPermission_Code', 'Berechtigung')
            ->setSelectOptions($this->getModel()->getPermissionList($this->getUser()->getPermission_List()));
    }

    public function deleteAction()
    {
        $viewId = $this->getControllerRequest()->getViewIdMap();
        unset($viewId['UserPermission_Code']);
        $edit = $this->initDeleteTemplate($this->getRoleDetailRedirectPath()->setViewIdMap($viewId)->getPath());
        $edit->getComponentModel()->setComponentDataBean($this->getModel()->getFinder()->getBean());
    }

    protected function getRoleDetailRedirectPath(): PathHelper
    {
        return $this->getPathHelper()->setController('role')->setAction('detail')->setViewIdMap($this->getControllerRequest()->getViewIdMap());
    }

}
