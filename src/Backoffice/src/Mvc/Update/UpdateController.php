<?php


namespace Backoffice\Mvc\Update;

use Backoffice\Database\Updater\AbstractUpdater;
use Backoffice\Mvc\Base\BaseController;
use Mezzio\Mvc\View\ComponentDataBean;
use Mezzio\Mvc\View\ComponentModel;
use Mezzio\Mvc\View\Components\Base\AbstractComponent;
use Mezzio\Mvc\View\Components\Edit\Edit;


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
        if ($this->checkPermission('update.schema')) {
            $this->getModel()->addOption(UpdateModel::OPTION_SCHEMA_ALLOWED);
        }
        if ($this->checkPermission('update.data')) {
            $this->getModel()->addOption(UpdateModel::OPTION_DATA_ALLOWED);
        }
        if (!$this->checkPermission('update')) {
            throw new \Exception('Unauthorized');
        }
    }


    public function indexAction()
    {
        $this->getView()->getViewModel()->setTitle('Updates');
        $navigation = new \Mezzio\Mvc\View\Components\Navigation\Navigation('Datenbank Updates', new ComponentModel());
        $dataComponent = $this->getUpdaterComponent($this->getModel()->getDataUpdater(), 'Daten Update', 'data');
        $dataComponent->setPermission('update.data');
        $navigation->addComponent($dataComponent);
        $schemaComponent = $this->getUpdaterComponent($this->getModel()->getSchemaUpdater(),'Schema Update', 'schema');
        $schemaComponent->setPermission('update.schema');
        $navigation->addComponent($schemaComponent);
        $navigation->setPermission('update');
        $navigation->setActive($this->getNavigationState($navigation->getId()));
        $this->getView()->addComponent($navigation);
    }

    public function getUpdaterComponent(AbstractUpdater $updater, string $title, string $submitAction)
    {
        $previewList = $updater->getPreviewMap();
        $edit = new Edit($title, new ComponentModel());
        $edit->getComponentModel()->setComponentDataBean(new ComponentDataBean());
        $edit->getComponentModel()->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());

        $edit->setCols(1);
        foreach ($previewList as $key => $item) {
            $edit->addCheckbox($key, 'Update Methode: ' . $key)
                ->setValue($key)
                ->setChecked(true)
                ->setHint('<pre>' . json_encode($item, JSON_PRETTY_PRINT) . '</pre>');
        }
        $edit->addSubmit($submitAction, 'Update');
        return $edit;
    }
}
