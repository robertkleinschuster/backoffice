<?php


namespace Backoffice\Mvc\Controller;



use Backoffice\Mvc\Formatter\RoleBeanFormatter;
use Mezzio\Mvc\Controller\ControllerRequest;
use Mezzio\Mvc\View\ComponentDataBean;
use Mezzio\Mvc\View\Components\Edit\Edit;
use Mezzio\Mvc\View\Components\Edit\Fields\Link;
use Mezzio\Mvc\View\Components\Overview\Overview;
use Mezzio\Mvc\View\Components\Toolbar\Toolbar;

/**
 * Class UserRoleController
 * @package Backoffice\Mvc\Controller
 */
class UserRoleController extends BaseController
{
    protected function initView()
    {
        parent::initView();
        $this->setActiveNavigation('userrole', 'index');
    }


    public function indexAction()
    {
        $this->getView()->getViewModel()->setTitle('Rollen Übersicht');
        $toolbar = new Toolbar();
        $toolbar->getComponentModel()->setComponentDataBean(new ComponentDataBean());
        $toolbar->addButton($this->getPathHelper()->setAction('create')->getPath(), 'Neu');
        $this->getView()->addComponent($toolbar);

        $overview = new Overview();
        $overview->setBeanFormatter(new RoleBeanFormatter());
        $overview->getComponentModel()->setComponentDataBeanList($this->getModel()->getFinder()->getBeanList());
        $overview->addText('UserRole_Code', 'Code');
        $overview->addBadge('UserRole_Active', 'Status');

        $this->getView()->addComponent($overview);
    }

    public function createAction()
    {
        $this->getView()->getViewModel()->setTitle('Rolle erstellen');
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $edit = $this->addEditFields();
        $edit->getComponentModel()->setComponentDataBean($bean);
        $edit->getComponentModel()->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->addSubmit('save', 'Erstellen');
        $edit->addLink('cancel', 'Abbrechen')
            ->setAction($this->getPathHelper()->setAction('index')->getPath())
            ->setAppendToColumnPrevious(true)
            ->setStyle(Link::STYLE_SECONDARY)->setValue('Abbrechen');
        $edit->addSubmitAttribute(ControllerRequest::ATTRIBUTE_REDIRECT, $this->getPathHelper()->setAction('index')->getPath());
        $edit->addSubmitAttribute(ControllerRequest::ATTRIBUTE_CREATE, 'create');
        $this->getView()->addComponent($edit);
        foreach ($edit->getFieldList() as $item) {
            $bean->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey()));
        }
        $bean->setData('UserRole_Active', true);
        $bean->setFromArray($this->getPreviousAttributes());
    }

    public function editAction()
    {

    }

    protected function addEditFields($model=null) {
        $edit = new Edit('', $model);
        $edit->addText('UserRole_Code', 'Code');
        return $edit;
    }
}
