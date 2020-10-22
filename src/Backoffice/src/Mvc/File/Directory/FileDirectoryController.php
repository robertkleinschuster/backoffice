<?php
namespace Backoffice\Mvc\File\Directory;

use Backoffice\Mvc\Base\BackofficeBeanFormatter;
use Backoffice\Mvc\Base\BaseController;
use Mvc\Helper\PathHelper;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Overview\Fields\Link;
use Mvc\View\Components\Overview\Overview;
use NiceshopsDev\Bean\BeanInterface;

class FileDirectoryController extends BaseController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('filedirectory.create', 'filedirectory.edit', 'filedirectory.delete');
    }


    public function isAuthorized(): bool
    {
        return $this->checkPermission('filedirectory');
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
        $edit->getBean()->setFromArray($this->getPreviousAttributes());
    }

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setViewIdMap(['FileDirectory_Code' => '{FileDirectory_Code}']);
    }

    public function deleteAction()
    {
        $delete = $this->initDeleteTemplate();
        $delete->setBean($this->getModel()->getFinder()->getBean());
    }


    protected function addOverviewFields(Overview $overview): void
    {
        parent::addOverviewFields($overview);
        $overview->addBadgeBoolean(
            'FileDirectory_Active',
            $this->translate('filedirectory.active'),
            $this->translate('filedirectory.active.true'),
            $this->translate('filedirectory.active.false')
        );
        $overview->addText('FileDirectory_Code', $this->translate('filedirectory.code'));
        $overview->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);
        $detail->addBadgeBoolean(
            'FileDirectory_Active',
            $this->translate('filedirectory.active'),
            $this->translate('filedirectory.active.true'),
            $this->translate('filedirectory.active.false')
        );
        $detail->addText('FileDirectory_Code', $this->translate('filedirectory.code'));
        $detail->addText('FileDirectory_Name', $this->translate('filedirectory.name'));

    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addText('FileDirectory_Code', $this->translate('filedirectory.code'));
        $edit->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
        $edit->addCheckbox('FileDirectory_Active', $this->translate('filedirectory.active'));
    }


}
