<?php


namespace Backoffice\Mvc\Cms\SiteParagraph;


use Backoffice\Mvc\Base\BaseController;
use Mvc\View\Components\Edit\Edit;

class CmsSiteParagraphController extends BaseController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmssiteparagraph.create', 'cmssiteparagraph.edit', 'cmssiteparagraph.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmssiteparagraph');
    }


    public function createAction()
    {
        $create = $this->initCreateTemplate($this->getSiteRedirectPath()->getPath());
        $create->setBean($this->getModel()->getFinder()->getFactory()->createBean());
    }

    public function deleteAction()
    {
        $viewId = $this->getControllerRequest()->getViewIdMap();
        unset($viewId['CmsParagraph_ID']);
        $delete = $this->initDeleteTemplate($this->getSiteRedirectPath()->setViewIdMap($viewId)->getPath());
        $delete->setBean($this->getModel()->getFinder()->getBean());
    }

    public function orderAction()
    {
        if ($this->getControllerRequest()->getAttribute('order') == 'up') {
            $this->getModel()->orderUp();
        }
        if ($this->getControllerRequest()->getAttribute('order') == 'down') {
            $this->getModel()->orderDown();
        }
        $viewId = $this->getControllerRequest()->getViewIdMap();
        unset($viewId['CmsParagraph_ID']);
        $this->getControllerResponse()->setRedirect($this->getSiteRedirectPath()->setViewIdMap($viewId)->getPath());
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addSelect('CmsParagraph_ID', $this->translate('cmsparagraph.name'))
        ->setSelectOptions($this->getModel()->getParagraph_Options());
    }

    protected function getSiteRedirectPath()
    {
        return $this->getPathHelper()->setController('cmssite')->setAction('detail')->setViewIdMap($this->getControllerRequest()->getViewIdMap());
    }

}
