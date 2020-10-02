<?php


namespace Backoffice\Mvc\Controller;



use Backoffice\Authorization\Permission\PermissionBeanFinder;
use Backoffice\Mvc\Formatter\RoleBeanFormatter;
use Mezzio\Mvc\Controller\ControllerRequest;
use Mezzio\Mvc\Controller\ControllerResponse;
use Mezzio\Mvc\View\ComponentDataBean;
use Mezzio\Mvc\View\Components\Detail\Detail;
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

        $path = $this->getPathHelper()->setViewIdMap(['UserRole_ID' => '{UserRole_ID}']);
        $overview->addLink('', '')
            ->setAction($path->setAction('detail')
                ->getPath(false))
            ->setValue("<i class=\"fas fa-search\"></i>")
            ->setChapter('actions')->setWidth(30);
        $overview->addText('UserRole_Code', 'Code');
        $overview->addBadge('UserRole_Active', 'Status');

        $this->getView()->addComponent($overview);
    }

    public function createAction()
    {
        $this->getView()->getViewModel()->setTitle('Rolle erstellen');
        $bean = $this->getModel()->getFinder()->getFactory()->createBean();
        $edit = new Edit();
        $edit->addText('UserRole_Code', 'Code');
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

    public function detailAction() {
        $this->getView()->getViewModel()->setTitle('Rolle');
        $bean = $this->getModel()->getFinder()->getBean();
        $detail = new Detail();
        $detail->getComponentModel()->setComponentDataBean($bean);
        $detail->addText('UserRole_Code', 'Code');

        $this->getView()->addComponent($detail);

        $toolbar = new Toolbar('Berechtigungen');
        $toolbar->getComponentModel()->setComponentDataBean(new ComponentDataBean());
        $toolbar->addButton(
            $this->getPathHelper()
                ->setAction('linktopermission')
                ->setParams(['UserRole_ID' => $bean->getData('UserRole_ID')])
                ->getPath(),
            'Hinzufügen');
        $this->getView()->addComponent($toolbar);

        $overview = new Overview();
        $overview->addText('UserPermission_Code', 'Code');
        $overview->getComponentModel()->setComponentDataBeanList($bean->getData('UserPermission_BeanList'));
        $this->getView()->addComponent($overview);
    }

    public function linktopermissionAction()
    {
        $finder = new PermissionBeanFinder($this->getModel()->getDbAdpater());
        $finder->find();
        $beanList = $finder->getBeanList();
        $permissionOptions = [];
        foreach ($beanList as $roleBean) {
            $permissionOptions[$roleBean->getData('UserPermission_Code')] = $roleBean->getData('UserPermission_Code');
        }
        $this->getView()->getViewModel()->setTitle('Berechtigung hinzufügen');

        $edit = new Edit();
        $edit->getComponentModel()->addComponentDataBean(new ComponentDataBean());
        $edit->addSelect('UserPermission_Code', 'Berechtigung')
            ->setSelectOptions($permissionOptions);
        $edit->addSubmit('linktopermission', 'Hinzufügen');
        $roleId = $this->getControllerRequest()->getAttribute('UserRole_ID');
        $edit->addSubmitAttribute('UserRole_ID', $roleId);
        $edit->addSubmitAttribute(
            ControllerRequest::ATTRIBUTE_REDIRECT,
            $this->getPathHelper()
                ->setController('userrole')
                ->setAction('detail')
                ->setViewIdMap(['UserRole_ID' => $roleId])
                ->getPath()
        );
        $this->getView()->addComponent($edit);
    }


    public function linktouserAction()
    {
        $beanList = $this->getModel()->getFinder()->getBeanList();
        $roleOptions = [];
        foreach ($beanList as $roleBean) {
            $roleOptions[$roleBean->getData('UserRole_ID')] = $roleBean->getData('UserRole_Code');
        }

        $this->getView()->getViewModel()->setTitle('Rolle hinzufügen');
        $edit = new Edit();
        $edit->getComponentModel()->addComponentDataBean(new ComponentDataBean());
        $edit->addSelect('UserRole_ID', 'Rolle')
            ->setSelectOptions($roleOptions);
        $edit->addSubmit('linktouser', 'Hinzufügen');
        $personId = $this->getControllerRequest()->getAttribute('Person_ID');
        $edit->addSubmitAttribute('Person_ID', $personId);
        $edit->addSubmitAttribute(
            ControllerRequest::ATTRIBUTE_REDIRECT,
            $this->getPathHelper()
                ->setController('user')
                ->setAction('detail')
                ->setViewIdMap(['Person_ID' => $personId])
            ->getPath()
        );
        $this->getView()->addComponent($edit);

    }

}
