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

    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('rolepermission.create', 'rolepermission.edit', 'rolepermission.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('rolepermission');
    }


    public function createAction()
    {
        $this->getView()->setHeading($this->translate('rolepermission.create.title'));
        $edit = $this->initCreateTemplate($this->getRoleDetailRedirectPath()->getPath());
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $edit->setBean($bean);
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addSelect('UserPermission_Code', $this->translate('userpermission.code'))
            ->setSelectOptions($this->getModel()->getPermissionList($this->getUser()->getPermission_List(), $this->getControllerRequest()->getViewIdMap()));
    }

    public function deleteAction()
    {
        $viewId = $this->getControllerRequest()->getViewIdMap();
        unset($viewId['UserPermission_Code']);
        $edit = $this->initDeleteTemplate($this->getRoleDetailRedirectPath()->setViewIdMap($viewId)->getPath());
        $edit->setBean($this->getModel()->getFinder()->getBean());
    }

    protected function getRoleDetailRedirectPath(): PathHelper
    {
        return $this->getPathHelper()->setController('role')->setAction('detail')->setViewIdMap($this->getControllerRequest()->getViewIdMap());
    }

}
