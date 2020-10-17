<?php
namespace Backoffice\Mvc\Cms\Menu;

use Backoffice\Mvc\Base\BackofficeBeanFormatter;
use Backoffice\Mvc\Base\BaseController;
use Mvc\Helper\PathHelper;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Overview\Overview;

class CmsMenuController extends BaseController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmsmenu.create', 'cmsmenu.edit', 'cmsmenu.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmsmenu');
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
        return $this->getPathHelper()->setViewIdMap(['CmsMenu_ID' => '{CmsMenu_ID}']);
    }


    protected function addOverviewFields(Overview $overview): void
    {
        parent::addOverviewFields($overview);
        $overview->addText('Translation_Code_Title', $this->translate('cmsmenu.title'));

    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);
        $detail->addText('Translation_Code_Title', $this->translate('cmsmenu.title'));

    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addText('Translation_Code_Title', $this->translate('cmsmenu.title'));
    }

}
