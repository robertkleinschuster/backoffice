<?php

namespace Pars\Backoffice\Mvc\Base;

use Pars\Base\Localization\Locale\LocaleBeanFinder;
use Pars\Mvc\Helper\PathHelper;
use Pars\Mvc\Parameter\PaginationParameter;
use Pars\Mvc\Parameter\RedirectParameter;
use Pars\Mvc\View\Components\Alert\Alert;
use Pars\Mvc\View\Components\Base\Fields\AbstractButton;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;
use Pars\Mvc\View\Components\Pagination\Pagination;
use Pars\Mvc\View\Components\Toolbar\Fields\Link;
use Pars\Mvc\View\Components\Toolbar\Toolbar;

abstract class CrudController extends BaseController
{

    protected function initOverviewTemplate()
    {
        $this->getView()->setHeading($this->translate('overview.title'));
        $toolbar = new Toolbar();
        $button = $toolbar
            ->addButton($this->getCreatePath()->getPath(), $this->translate('overview.create'));
        if ($this->hasAttribute(self::ATTRIBUTE_CREATE_PERMISSION)) {
            $button->setPermission($this->getAttribute(self::ATTRIBUTE_CREATE_PERMISSION));
        }
        $this->getView()->addComponent($toolbar);
        $overview = new Overview();
        $overview->setBeanConverter(new BackofficeBeanConverter());
        $path = $this->getDetailPath();
        $overview->addDetailIcon($path->setAction('detail')->getPath(false))
            ->setWidth(122);
        $editButton = $overview->addEditIcon($path->setAction('edit')->getPath(false));
        if ($this->hasAttribute(self::ATTRIBUTE_EDIT_PERMISSION)) {
            $editButton->setPermission($this->getAttribute(self::ATTRIBUTE_EDIT_PERMISSION));
        }
        $deleteButton = $overview->addDeleteIcon($path->setAction('delete')->getPath(false));
        if ($this->hasAttribute(self::ATTRIBUTE_DELETE_PERMISSION)) {
            $deleteButton->setPermission($this->getAttribute(self::ATTRIBUTE_DELETE_PERMISSION));
        }
        $this->addOverviewFields($overview);
        $this->getView()->addComponent($overview);

        if ($this->getModel()->getBeanFinder()->hasLimit()) {
            $limit = $this->getModel()->getBeanFinder()->getLimit();
            $pages = $this->getModel()->getBeanFinder()->count() / $limit;
            if ($pages > 1) {
                $pagination = new Pagination();
                for ($i = 0; $i < $pages; $i++) {
                    $link = $this->getPathHelper()
                        ->addParameter((new PaginationParameter())->setPage($i + 1)->setLimit($limit))
                        ->getPath();
                    $pagination->addLink($link);
                }
                if ($this->getControllerRequest()->hasPagingation()) {
                    $page = $this->getControllerRequest()->getPagination()->getPage();
                    $page = $page > 0 ? $page : 1;
                    $pagination->setActive($page - 1);
                }
                $this->getView()->addComponent($pagination);
            }
        }
        return $overview;
    }

    protected function initDetailTemplate()
    {
        $this->addLocaleButtons();
        $this->getView()->setHeading($this->translate('detail.title'));
        if (!$this->getControllerRequest()->hasId()) {
            $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('index')->getPath());
        }
        $toolbar = new Toolbar();
        $toolbar->addButton($this->getIndexPath()->getPath(),$this->translate('detail.back'))->setIcon(Link::ICON_ARROW_LEFT)->setStyle(Link::STYLE_INFO);
        $this->getView()->addComponent($toolbar);
        $detail = new Detail();
        $detail->setBeanConverter(new BackofficeBeanConverter());
        $this->addDetailFields($detail);
        $this->getView()->addComponent($detail);
        return $detail;
    }


    protected function initCreateTemplate(string $redirect = null)
    {
        if (null === $redirect) {
            $redirect = $this->getIndexPath()->getPath();
        }
        $this->getView()->setHeading($this->translate('create.title'));
        $edit = new Edit();
        $this->addEditFields($edit);
        $edit->addSubmitCreate($this->translate('create.submit'), (new RedirectParameter())->setLink($redirect));
        $edit->addCancel($redirect, $this->translate('create.cancel'), true);
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->addComponent($edit);
        return $edit;
    }

    protected function addLocaleButtons()
    {
        $localeFinder = new LocaleBeanFinder($this->getModel()->getDbAdpater());
        $localeFinder->setLocale_Active(true);
        foreach ($localeFinder->getBeanListDecorator() as $locale) {
            if ($locale->getData('Locale_Code') != $this->getTranslator()->getLocale()) {
                $langPath = $this->getPathHelper()
                    ->setId($this->getControllerRequest()->getId())
                    ->addRouteParameter('locale', $locale->getData('Locale_UrlCode'))
                    ->getPath();
                $this->getView()->getToolbar()->addButton($langPath, $locale->getData('Locale_Name'))
                    ->setStyle(AbstractButton::STYLE_WARNING);
            }
        }
    }

    protected function initEditTemplate(string $redirect = null)
    {
        if (null === $redirect) {
            $redirect = $this->getIndexPath()->getPath();
        }
        $this->addLocaleButtons();
        $this->getView()->setHeading($this->translate('edit.title'));
        $edit = new Edit();
        $this->addEditFields($edit);
        $edit->addSubmitSave($this->translate('edit.submit'), (new RedirectParameter())->setLink($redirect));
        $edit->addCancel($redirect, $this->translate('edit.cancel'), true);
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->addComponent($edit);
        return $edit;
    }


    public function initDeleteTemplate(string $redirect = null)
    {
        if (null === $redirect) {
            $redirect = $this->getIndexPath()->getPath();
        }
        $this->getView()->setHeading($this->translate('delete.title'));
        $alert = new Alert();
        $alert->addText("", "")->setValue($this->translate('delete.message'));
        $this->getView()->addComponent($alert);
        $edit = new Edit();
        $edit->addSubmitDelete($this->translate('delete.submit'), (new RedirectParameter())->setLink($redirect));
        $edit->addCancel($redirect, $this->translate('delete.cancel'), true);
        $this->getView()->addComponent($edit);
        return $edit;
    }


    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setAction('detail');
    }

    protected function getCreatePath(): PathHelper
    {
        return $this->getDetailPath()->setAction('create')->resetId();
    }

    protected function getEditPath(): PathHelper
    {
        return $this->getDetailPath()->setAction('edit');
    }

    protected function getIndexPath(): PathHelper
    {
        return $this->getPathHelper()->setAction('index');
    }

    public function indexAction()
    {
        $overview = $this->initOverviewTemplate();
        $overview->setBeanList($this->getModel()->getBeanList());
    }

    public function detailAction()
    {
        $detail = $this->initDetailTemplate();
        $detail->setBean($this->getModel()->getBean());
    }

    public function createAction()
    {
        $create = $this->initCreateTemplate();
        $create->setBean($this->getModel()->getEmptyBean());
        foreach ($create->getFieldList() as $item) {
            $create->getBean()->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey(), true));
        }
        $create->setBean(
            $this->getModel()->getBeanConverter()
                ->convert($create->getBean(), $this->getPreviousAttributes())
        );
    }

    public function editAction()
    {
        $edit = $this->initEditTemplate();
        $edit->setBean(
            $this->getModel()->getBeanConverter()
                ->convert($this->getModel()->getBean(), $this->getPreviousAttributes())->toBean()
        );
    }


    public function deleteAction()
    {
        $delete = $this->initDeleteTemplate();
        $delete->setBean($this->getModel()->getBean());
    }


    /**
     * @param Overview $overview
     */
    abstract protected function addOverviewFields(Overview $overview): void;

    /**
     * @param Detail $detail
     * @return mixed
     */
    abstract protected function addDetailFields(Detail $detail): void;

    /**
     * @param Edit $edit
     * @return mixed
     */
    abstract protected function addEditFields(Edit $edit): void;
}
