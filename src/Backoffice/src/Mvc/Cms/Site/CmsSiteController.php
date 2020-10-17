<?php


namespace Backoffice\Mvc\Cms\Site;


use Backoffice\Mvc\Base\BackofficeBeanFormatter;
use Mvc\Helper\PathHelper;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Overview\Overview;

class CmsSiteController extends \Backoffice\Mvc\Base\BaseController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmssite.create', 'cmssite.edit', 'cmssite.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmssite');
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
        return $this->getPathHelper()->setViewIdMap(['CmsSite_ID' => '{CmsSite_ID}']);
    }

    protected function initEditTemplate(string $redirect = null)
    {
        return parent::initEditTemplate($redirect);
    }

    protected function addOverviewFields(Overview $overview): void
    {
        parent::addOverviewFields($overview);
    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
    }
}
