<?php


namespace Backoffice\Mvc\Update;

use Base\Database\Updater\AbstractUpdater;
use Backoffice\Mvc\Base\BaseController;
use Mvc\View\ComponentDataBean;
use Mvc\View\ComponentModel;
use Mvc\View\Components\Base\AbstractComponent;
use Mvc\View\Components\Edit\Edit;


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
        $this->getView()->setHeading('Updates');
        $navigation = new \Mvc\View\Components\Navigation\Navigation($this->translate('update.database'));
        $dataComponent = $this->getUpdaterComponent($this->getModel()->getDataUpdater(), $this->translate('update.database.data'), 'data');
        $dataComponent->setPermission('update.data');
        $navigation->addComponent($dataComponent);
        $schemaComponent = $this->getUpdaterComponent($this->getModel()->getSchemaUpdater(),$this->translate('update.database.schema'), 'schema');
        $schemaComponent->setPermission('update.schema');
        $navigation->addComponent($schemaComponent);
        $navigation->setPermission('update');
        $navigation->setActive($this->getNavigationState($navigation->getId()));
        $this->getView()->addComponent($navigation);
    }

    public function getUpdaterComponent(AbstractUpdater $updater, string $title, string $submitAction)
    {
        $previewList = $updater->getPreviewMap();
        $edit = new Edit($title);
        $edit->setBean(new ComponentDataBean());
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());

        $edit->setCols(1);
        foreach ($previewList as $key => $item) {
            $edit->addCheckbox($key, 'Update Methode: ' . $key)
                ->setValue($key)
                ->setChecked(true)
                ->setHint('<pre>' . json_encode($item, JSON_PRETTY_PRINT) . '</pre>');
        }
        $edit->addSubmit($submitAction, $this->translate('update.submit'));
        return $edit;
    }
}
