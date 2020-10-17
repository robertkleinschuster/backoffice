<?php


namespace Backoffice\Mvc\Translation;


use Backoffice\Mvc\Base\BackofficeBeanFormatter;
use Backoffice\Mvc\Base\BaseController;
use Base\Localization\LocalizationMiddleware;
use Mvc\Helper\PathHelper;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Overview\Overview;

/**
 * Class TranslationController
 * @package Backoffice\Mvc\Translation
 * @method TranslationModel getModel()
 */
class TranslationController extends BaseController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('translation.create', 'translation.edit', 'translation.delete');
    }


    public function isAuthorized(): bool
    {
        return $this->checkPermission('translation');
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
        $create->getBean()->setFromArray($this->getPreviousAttributes());
        foreach ($create->getFieldList() as $item) {
            $create->getBean()->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey()));
        }
    }

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setViewIdMap(['Translation_ID' => '{Translation_ID}']);
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

    protected function addOverviewFields(Overview $overview): void
    {
        parent::addOverviewFields($overview);
        $overview->addText('Locale_Code', $this->translate('translation.locale'));
        $overview->addText('Translation_Code', $this->translate('translation.code'));
        $overview->addText('Translation_Text', $this->translate('translation.text'));
        $overview->addText('Translation_Namespace', $this->translate('translation.namespace'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);
        $detail->addText('Locale_Code', $this->translate('translation.locale'));
        $detail->addText('Translation_Code', $this->translate('translation.code'));
        $detail->addText('Translation_Text', $this->translate('translation.text'));
        $detail->addText('Translation_Namespace', $this->translate('translation.namespace'));
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addSelect('Locale_Code', $this->translate('translation.locale'))
        ->setSelectOptions($this->getModel()->getLocale_Options())
        ->setValue($this->getTranslator()->getLocale());
        $edit->addText('Translation_Code', $this->translate('translation.code'));
        $edit->addText('Translation_Namespace', $this->translate('translation.namespace'))
            ->setValue('default');
        $edit->addTextarea('Translation_Text', $this->translate('translation.text'))
        ->setRows(5);
    }


}
