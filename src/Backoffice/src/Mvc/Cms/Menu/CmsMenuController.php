<?php

namespace Pars\Backoffice\Mvc\Cms\Menu;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Backoffice\Mvc\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Mvc\Parameter\IdParameter;
use Pars\Mvc\Parameter\MoveParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;

/**
 * Class CmsMenuController
 * @package Pars\Backoffice\Mvc\Cms\Menu
 * @method CmsMenuModel getModel()
 */
class CmsMenuController extends CrudController
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

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setId((new IdParameter())->addId('CmsMenu_ID'));
    }

    protected function addOverviewFields(Overview $overview): void
    {
        $overview->addMoveDownIcon($this->getPathHelper()->addParameter((new MoveParameter())->setDown('CmsMenu_Order'))->getPath())
            ->setWidth(85)
            ->setShow(function (BeanInterface $bean) {
                return $bean->hasData('CmsMenu_Order')
                    && $bean->getData('CmsMenu_Order') < $this->getModel()->getBeanFinder()->count();
            });
        $overview->addMoveUpIcon($this->getPathHelper()->addParameter((new MoveParameter())->setUp('CmsMenu_Order'))->getPath())
            ->setShow(function (BeanInterface $bean) {
                return $bean->hasData('CmsMenu_Order') && $bean->getData('CmsMenu_Order') > 1;
            });


        $overview->addText('Article_Code', $this->translate('article.code'))->setWidth(150);
        $overview->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $overview->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        $detail->addText('Article_Code', $this->translate('article.code'));
        $detail->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $detail->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'));
    }

    protected function addEditFields(Edit $edit): void
    {
        $edit->addSelect('CmsSite_ID', $this->translate('articletranslation.name'))
            ->setSelectOptions($this->getModel()->getCmsSite_Options());
        $edit->addSelect('CmsMenuState_Code', $this->translate('articlestate.code'))
            ->setSelectOptions($this->getModel()->getCmsMenuState_Options());
        $edit->addSubmitAttribute('CmsMenuType_Code', 'header');
    }
}
