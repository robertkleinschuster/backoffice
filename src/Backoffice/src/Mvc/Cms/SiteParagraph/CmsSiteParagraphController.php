<?php

namespace Pars\Backoffice\Mvc\Cms\SiteParagraph;

use Pars\Backoffice\Mvc\Base\CrudController;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;

class CmsSiteParagraphController extends CrudController
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


    protected function addEditFields(Edit $edit): void
    {
        $edit->addSelect('CmsParagraph_ID', $this->translate('cmsparagraph.name'))
        ->setSelectOptions($this->getModel()->getParagraph_Options());
    }

    protected function addOverviewFields(Overview $overview): void
    {
        // TODO: Implement addOverviewFields() method.
    }

    protected function addDetailFields(Detail $detail): void
    {
        // TODO: Implement addDetailFields() method.
    }


    protected function getSiteRedirectPath()
    {
        return $this->getPathHelper()->setController('cmssite')->setAction('detail')->setViewIdMap($this->getControllerRequest()->getViewIdMap());
    }
}
