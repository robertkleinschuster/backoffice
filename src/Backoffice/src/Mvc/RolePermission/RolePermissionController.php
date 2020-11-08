<?php

namespace Pars\Backoffice\Mvc\RolePermission;

use Pars\Backoffice\Mvc\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;

/**
 * Class RolePermissionController
 * @package Pars\Backoffice\Mvc\RolePermission
 * @method RolePermissionModel getModel()
 */
class RolePermissionController extends CrudController
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

    protected function addEditFields(Edit $edit): void
    {
        $edit->addSelect('UserPermission_Code', $this->translate('userpermission.code'))
            ->setSelectOptions(
                $this->getModel()->getPermissionList(
                    $this->getUser()->getPermission_List(),
                    $this->getControllerRequest()->getId()
                )
            );
    }

    protected function addOverviewFields(Overview $overview): void
    {
        // TODO: Implement addOverviewFields() method.
    }

    protected function addDetailFields(Detail $detail): void
    {
        // TODO: Implement addDetailFields() method.
    }

    public function deleteAction()
    {
        $idParameter = $this->getControllerRequest()->getId();
        $idParameter->unsetAttribute('UserPermission_Code');
        $edit = $this->initDeleteTemplate($this->getRoleDetailRedirectPath()->setId($idParameter)->getPath());
        $edit->setBean($this->getModel()->getBeanFinder()->getBean());
    }

    protected function getRoleDetailRedirectPath(): PathHelper
    {
        return $this->getPathHelper()
            ->setController('role')
            ->setAction('detail')
            ->setId($this->getControllerRequest()->getId());
    }
}
