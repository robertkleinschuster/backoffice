<?php


namespace Backoffice\Mvc\Controller;

use Backoffice\Mvc\Model\UpdateModel;
use Mezzio\Mvc\View\ComponentDataBean;
use Mezzio\Mvc\View\ComponentDataBeanList;
use Mezzio\Mvc\View\ComponentModel;
use Mezzio\Mvc\View\Components\Edit\Edit;
use Mezzio\Mvc\View\Components\Overview\Overview;

/**
 * Class UpdateController
 * @package Backoffice\Mvc\Controller
 * @method UpdateModel getModel()
 */
class UpdateController extends BaseController
{
    protected function initView()
    {
        parent::initView();
        $this->setActiveNavigation('update', 'index');

    }


    public function indexAction()
    {
        $this->getView()->getViewModel()->setTitle('Update');
        $beanList = new ComponentDataBeanList();
        $bean = new ComponentDataBean();
        $bean->setData('Gruppe', 'Datenbank');
        $bean->setData('Typ', 'Schema');
        $bean->setData('action', 'schema');
        $beanList->addBean($bean);

        $bean = new ComponentDataBean();
        $bean->setData('Gruppe', 'Datenbank');
        $bean->setData('Typ', 'Daten');
        $bean->setData('action', 'data');
        $beanList->addBean($bean);

        $componentModel = new ComponentModel();
        $componentModel->setComponentDataBeanList($beanList);
        $overview = new Overview('', $componentModel);

        $overview->addLink('Gruppe', 'Gruppe')
            ->setAction($this->getPathHelper()->setAction('{action}')->getPath())
            ->setWidth(100);
        $overview->addLink('Typ', 'Typ')
            ->setAction($this->getPathHelper()->setAction('{action}')->getPath());
        $this->getView()->addComponent($overview);
    }


    public function schemaAction()
    {
        $this->getView()->getViewModel()->setTitle('Schema Updater');
        $previewList = $this->getModel()->getSchemaUpdaterPreview();
        $edit = new Edit('', new ComponentModel());
        $edit->getComponentModel()->setComponentDataBean(new ComponentDataBean());
        $edit->getComponentModel()->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());

        $edit->setCols(1);
        foreach ($previewList as $key => $item) {
            $edit->addCheckbox($key, $key)
                ->setValue($key)
                ->setChecked(true)
                ->setHint('<pre>' . json_encode($item, JSON_PRETTY_PRINT) . '</pre>');
        }
        $edit->addSubmit('Update', 'schema');
        $this->getView()->addComponent($edit);
    }

    public function dataAction()
    {
        $this->getView()->getViewModel()->setTitle('Daten Updater');

        $previewList = $this->getModel()->getDataUpdaterPreview();
        $edit = new Edit('', new ComponentModel());
        $edit->getComponentModel()->setComponentDataBean(new ComponentDataBean());
        $edit->getComponentModel()->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());

        $edit->setCols(1);
        foreach ($previewList as $key => $item) {
            $edit->addCheckbox($key, $key)
                ->setValue($key)
                ->setChecked(true)
                ->setHint('<pre>' . json_encode($item, JSON_PRETTY_PRINT) . '</pre>');
        }
        $edit->addSubmit('Update', 'data');
        $this->getView()->addComponent($edit);
    }

}
