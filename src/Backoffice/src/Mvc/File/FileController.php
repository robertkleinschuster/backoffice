<?php
namespace Backoffice\Mvc\File;

use Backoffice\Mvc\Base\BackofficeBeanFormatter;
use Backoffice\Mvc\Base\BaseController;
use Mvc\Helper\PathHelper;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Detail\Fields\Image;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Overview\Fields\Link;
use Mvc\View\Components\Overview\Overview;
use NiceshopsDev\Bean\BeanInterface;

class FileController extends BaseController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('file.create', 'file.edit', 'file.delete');
    }


    public function isAuthorized(): bool
    {
        return $this->checkPermission('file');
    }


    public function indexAction()
    {
        $overview = $this->initOverviewTemplate(new BackofficeBeanFormatter());
        $overview->setBeanList($this->getModel()->getFinder()->getBeanGenerator());
    }
    public function detailAction()
    {
        $detail = $this->initDetailTemplate();
        $bean = $this->getModel()->getFinder()->getBean();
        $detail->setBean($bean);
        if (in_array($bean->getData('FileType_Code'), ['jpg', 'png'])) {
            $detail->addImage('File_Name', $this->translate('file.preview'))->setSource("/upload/{$bean->getData('FileDirectory_Code')}/{$bean->getData('File_Code')}")->setSize(Image::SIZE_THUMBNAIL);
        }

    }

    public function createAction()
    {
        if (isset($this->getControllerRequest()->getViewIdMap()['FileDirectory_ID'])) {
            $create = $this->initCreateTemplate($this->getPathHelper()->setController('filedirectory')->setAction('detail')->setViewIdMap($this->getControllerRequest()->getViewIdMap())->getPath());
        } else {
            $create = $this->initCreateTemplate();
        }
        $create->setBean($this->getModel()->getFinder()->getFactory()->createBean());
        foreach ($create->getFieldList() as $item) {
            $create->getBean()->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey()));
        }
        $create->getBean()->setFromArray($this->getPreviousAttributes());
    }

    public function editAction()
    {
        if (isset($this->getControllerRequest()->getViewIdMap()['FileDirectory_ID'])) {
            $edit = $this->initEditTemplate($this->getPathHelper()->setController('filedirectory')->setAction('detail')->setViewIdMap($this->getControllerRequest()->getViewIdMap())->getPath());
        }else {
            $edit = $this->initEditTemplate();
        }
        $edit->setBean($this->getModel()->getFinder()->getBean());
        $edit->getBean()->setFromArray($this->getPreviousAttributes());
    }

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setViewIdMap(['File_Code' => '{File_Code}']);
    }

    public function deleteAction()
    {
        if (isset($this->getControllerRequest()->getViewIdMap()['FileDirectory_ID'])) {
            $delete = $this->initDeleteTemplate($this->getPathHelper()->setController('filedirectory')->setAction('detail')->setViewIdMap($this->getControllerRequest()->getViewIdMap())->getPath());
        } else {
            $delete = $this->initDeleteTemplate();
        }
        $delete->setBean($this->getModel()->getFinder()->getBean());
    }


    protected function addOverviewFields(Overview $overview): void
    {
        parent::addOverviewFields($overview);
        $overview->addText('File_Name', $this->translate('file.name'));
        $overview->addText('FileType_Name', $this->translate('filetype.name'));
        $overview->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);
        $detail->addText('File_Name', $this->translate('file.name'));
        $detail->addText('FileType_Name', $this->translate('filetype.name'));
        $detail->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addSelect('FileType_Code', $this->translate('filetype.name'))
        ->setSelectOptions($this->getModel()->getFileType_Options());
        if (!isset($this->getControllerRequest()->getViewIdMap()['FileDirectory_ID'])) {
            $edit->addSelect('FileDirectory_ID', $this->translate('filedirectory.name'))
                ->setSelectOptions($this->getModel()->getFileDirectory_Options());
        }
        $edit->addText('File_Name', $this->translate('file.name'));
        $edit->addFile('Upload', $this->translate('file.upload'))->setShow(function(BeanInterface $bean) {
            return !$bean->hasData('File_ID') || !$bean->hasData('File_Code');
        });
    }
}
