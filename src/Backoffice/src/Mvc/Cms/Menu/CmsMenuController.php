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


    public function deleteAction()
    {
        $delete = $this->initDeleteTemplate();
        $delete->setBean($this->getModel()->getFinder()->getBean());
    }

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setViewIdMap(['CmsMenu_ID' => '{CmsMenu_ID}']);
    }


    protected function addOverviewFields(Overview $overview): void
    {
        parent::addOverviewFields($overview);
        $overview->addText('Article_Code', $this->translate('article.code'))->setWidth(100);
        $overview->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $overview->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'));

    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);
        $detail->addText('Article_Code', $this->translate('article.code'));
        $detail->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $detail->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'));
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addSelect('CmsSite_ID', $this->translate('articletranslation.name'))
        ->setSelectOptions($this->getModel()->getCmsSite_Options());
    }

}
