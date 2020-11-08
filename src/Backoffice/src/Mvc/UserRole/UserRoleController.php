<?php

namespace Pars\Backoffice\Mvc\UserRole;


use Pars\Backoffice\Mvc\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;

class UserRoleController extends CrudController
{
    protected function initView()
    {
        parent::initView();
        $this->setPermissions('userrole.create', 'userrole.edit', 'userrole.delete');
        if (!$this->checkPermission('userrole')) {
            throw new \Exception('Unauthorized');
        }
    }

    protected function addOverviewFields(Overview $overview): void
    {
        // TODO: Implement addOverviewFields() method.
    }

    protected function addDetailFields(Detail $detail): void
    {
        // TODO: Implement addDetailFields() method.
    }


    protected function addEditFields(Edit $edit): void
    {
        $edit->addSelect('UserRole_ID', 'Rolle')
            ->setSelectOptions($this->getModel()->getRoleList($this->getUser()->getPermission_List(), $this->getControllerRequest()->getViewIdMap()));
    }

    public function deleteAction()
    {
        $viewId = $this->getControllerRequest()->getId();
        $viewId->unsetAttribute('UserRole_ID');
        $edit = $this->initDeleteTemplate($this->getRoleDetailRedirectPath()->setId($viewId)->getPath());
        $edit->setBean($this->getModel()->getBeanFinder()->getBean());
    }

    protected function getRoleDetailRedirectPath(): PathHelper
    {
        return $this->getPathHelper()->setController('user')->setAction('detail');
    }
}
