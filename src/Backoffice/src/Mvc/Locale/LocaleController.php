<?php
namespace Backoffice\Mvc\Locale;

use Backoffice\Mvc\Base\BackofficeBeanFormatter;
use Backoffice\Mvc\Base\BaseController;
use Mvc\Helper\PathHelper;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Overview\Overview;

class LocaleController extends BaseController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('locale.create', 'locale.edit', 'locale.delete');
    }


    public function isAuthorized(): bool
    {
        return $this->checkPermission('locale');
    }


    public function indexAction()
    {
        $overview = $this->initOverviewTemplate(new BackofficeBeanFormatter());
        $overview->setBeanList($this->getModel()->getFinder()->getBeanGenerator());
    }
    public function detailAction()
    {
        $detail = $this->initDetailTemplate();
        $detail->setBean($this->getModel()->getFinder()->getBean());
    }

    public function createAction()
    {
        $create = $this->initCreateTemplate();
        $create->setBean($this->getModel()->getFinder()->getFactory()->createBean());
        foreach ($create->getFieldList() as $item) {
            $create->getBean()->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey()));
        }
        $create->getBean()->setFromArray($this->getPreviousAttributes());
    }

    public function editAction()
    {
        $edit = $this->initEditTemplate();
        $edit->setBean($this->getModel()->getFinder()->getBean());
    }

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setViewIdMap(['Locale_Code' => '{Locale_Code}']);
    }

    protected function addOverviewFields(Overview $overview): void
    {
        parent::addOverviewFields($overview);
        $overview->addBadgeBoolean(
            'Locale_Active',
            $this->translate('locale.active'),
            $this->translate('locale.active.true'),
            $this->translate('locale.active.false')
        );
        $overview->addText('Locale_Code', $this->translate('locale.code'));
        $overview->addText('Locale_Name', $this->translate('locale.name'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);
        $detail->addBadgeBoolean(
            'Locale_Active',
            $this->translate('locale.active'),
            $this->translate('locale.active.true'),
            $this->translate('locale.active.false')
        );
        $detail->addText('Locale_Code', $this->translate('locale.code'));
        $detail->addText('Locale_Name', $this->translate('locale.name'));

    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addText('Locale_Code', $this->translate('locale.code'));
        $edit->addText('Locale_Name', $this->translate('locale.name'));
        $edit->addCheckbox('Locale_Active', $this->translate('locale.active'));
    }


}
