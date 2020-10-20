<?php
namespace Backoffice\Mvc\Locale;

use Backoffice\Mvc\Base\BackofficeBeanFormatter;
use Backoffice\Mvc\Base\BaseController;
use Mvc\Helper\PathHelper;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Overview\Fields\Link;
use Mvc\View\Components\Overview\Overview;
use NiceshopsDev\Bean\BeanInterface;

class LocaleController extends BaseController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('locale.create', 'locale.edit', 'locale.delete');
    }


    public function isAuthorized(): bool
    {
        return $this->checkPermission('locale');
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
        return $this->getPathHelper()->setViewIdMap(['Locale_Code' => '{Locale_Code}']);
    }

    public function orderAction()
    {
        if ($this->getControllerRequest()->getAttribute('order') == 'up') {
            $this->getModel()->orderUp();
        }
        if ($this->getControllerRequest()->getAttribute('order') == 'down') {
            $this->getModel()->orderDown();
        }
        $this->getControllerResponse()->setRedirect($this->getPathHelper()->setAction('index')->getPath());
    }

    protected function addOverviewFields(Overview $overview): void
    {
        parent::addOverviewFields($overview);
        $overview->addLink('', '')
            ->setLink($this->getDetailPath()->setAction('order')->setParams(['order' => 'up'])->getPath())
            ->setValue('')
            ->setIcon(Link::ICON_ARROW_UP)
            ->setStyle(Link::STYLE_INFO)
            ->setOutline(true)
            ->addOption(Link::OPTION_TEXT_DECORATION_NONE)
            ->addOption(Link::OPTION_BUTTON_STYLE)
            ->setSize(Link::SIZE_SMALL)
            ->setChapter('order')
            ->setWidth(85)
            ->setShow(function(BeanInterface $bean){
                return $bean->hasData('Locale_Order') && $bean->getData('Locale_Order') > 1;

            });

        $overview->addLink('', '')
            ->setLink($this->getDetailPath()->setAction('order')->setParams(['order' => 'down'])->getPath())
            ->setValue('')
            ->setIcon(Link::ICON_ARROW_DOWN)
            ->setStyle(Link::STYLE_INFO)
            ->setOutline(true)
            ->addOption(Link::OPTION_TEXT_DECORATION_NONE)
            ->addOption(Link::OPTION_BUTTON_STYLE)
            ->setSize(Link::SIZE_SMALL)
            ->setChapter('order')->setShow(function(BeanInterface $bean){
                return $bean->hasData('Locale_Order') && $bean->getData('Locale_Order') < $this->getModel()->getFinder()->count();

            });
        $overview->addBadgeBoolean(
            'Locale_Active',
            $this->translate('locale.active'),
            $this->translate('locale.active.true'),
            $this->translate('locale.active.false')
        );
        $overview->addText('Locale_Code', $this->translate('locale.code'));
        $overview->addText('Locale_Name', $this->translate('locale.name'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);
        $detail->addBadgeBoolean(
            'Locale_Active',
            $this->translate('locale.active'),
            $this->translate('locale.active.true'),
            $this->translate('locale.active.false')
        );
        $detail->addText('Locale_Code', $this->translate('locale.code'));
        $detail->addText('Locale_Name', $this->translate('locale.name'));

    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addText('Locale_Code', $this->translate('locale.code'));
        $edit->addText('Locale_Name', $this->translate('locale.name'));
        $edit->addCheckbox('Locale_Active', $this->translate('locale.active'));
    }


}
