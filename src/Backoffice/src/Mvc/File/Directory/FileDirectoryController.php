<?php

namespace Pars\Backoffice\Mvc\File\Directory;


use Pars\Backoffice\Mvc\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Mvc\Parameter\IdParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;
use Pars\Mvc\View\Components\Toolbar\Toolbar;

class FileDirectoryController extends CrudController
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


    public function detailAction()
    {
        $detail = $this->initDetailTemplate();
        $bean = $this->getModel()->getBeanFinder()->getBean();
        $detail->setBean($bean);


        $toolbar = new Toolbar($this->translate('filedirectory.file.overview'));
        $toolbar->setBean($bean);
        $toolbar->addButton(
            $this->getPathHelper()->setController('file')->setAction('create')->setId((new IdParameter())->addId('FileDirectory_ID'))->getPath(),
            $this->translate('filedirectory.file.create')
        );

        $this->getView()->addComponent($toolbar);



        $overview = new Overview();
        $overview->addDetailIcon($this->getPathHelper()->setController('file')->setAction('detail')->setId((new IdParameter())->addId('File_ID')->addId('FileDirectory_ID'))->getPath())->setWidth(122);
        $overview->addEditIcon($this->getPathHelper()->setController('file')->setAction('edit')->setId((new IdParameter())->addId('File_ID')->addId('FileDirectory_ID'))->getPath());
        $overview->addDeleteIcon($this->getPathHelper()->setController('file')->setAction('delete')->setId((new IdParameter())->addId('File_ID')->addId('FileDirectory_ID'))->getPath());

        $overview->addText('File_Name', $this->translate('file.name'));
        $overview->addText('FileType_Name', $this->translate('filetype.name'));
        $overview->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
        $overview->setBeanList($bean->getData('File_BeanList'));
        $this->getView()->addComponent($overview);
    }




    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setId((new IdParameter())->addId('FileDirectory_ID'));
    }

    public function deleteAction()
    {
        $delete = $this->initDeleteTemplate();
        $delete->setBean($this->getModel()->getBeanFinder()->getBean());
    }


    protected function addOverviewFields(Overview $overview): void
    {
        $overview->addBadgeBoolean(
            'FileDirectory_Active',
            $this->translate('filedirectory.active'),
            $this->translate('filedirectory.active.true'),
            $this->translate('filedirectory.active.false')
        );
        $overview->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        $detail->addBadgeBoolean(
            'FileDirectory_Active',
            $this->translate('filedirectory.active'),
            $this->translate('filedirectory.active.true'),
            $this->translate('filedirectory.active.false')
        );
        $detail->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
    }

    protected function addEditFields(Edit $edit): void
    {
        $edit->addText('FileDirectory_Name', $this->translate('filedirectory.name'));
        $edit->addCheckbox('FileDirectory_Active', $this->translate('filedirectory.active'));
    }
}
