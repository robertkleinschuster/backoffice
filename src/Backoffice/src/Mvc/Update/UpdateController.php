<?php


namespace Backoffice\Mvc\Update;

use Backoffice\Database\Updater\AbstractUpdater;
use Backoffice\Mvc\Base\BaseController;
use Mezzio\Mvc\View\ComponentDataBean;
use Mezzio\Mvc\View\ComponentModel;
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
    }

    public function indexAction()
    {
        $this->getView()->getViewModel()->setTitle('Updates');
        $navigation = new \Mezzio\Mvc\View\Components\Navigation\Navigation('Datenbank Updates', new ComponentModel());
        $navigation->addComponent($this->getUpdaterComponent($this->getModel()->getDataUpdater(), 'Daten Update', 'data'));
        $navigation->addComponent($this->getUpdaterComponent($this->getModel()->getSchemaUpdater(),'Schema Update', 'schema'));
        $this->getView()->addComponent($navigation);
    }

    public function getUpdaterComponent(AbstractUpdater $updater, string $title, string $submitAction) {
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
