<?php


namespace Backoffice\Mvc\Cms\Site;


use Backoffice\Mvc\Base\BackofficeBeanFormatter;
use Mvc\Helper\PathHelper;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Overview\Overview;

/**
 * Class CmsSiteController
 * @package Backoffice\Mvc\Cms\Site
 * @method CmsSiteModel getModel()
 */
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

    public function deleteAction()
    {
        $delete = $this->initDeleteTemplate();
        $delete->setBean($this->getModel()->getFinder()->getBean());
    }


    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setViewIdMap(['CmsSite_ID' => '{CmsSite_ID}']);
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
        $detail->addText('ArticleType_Code', $this->translate('articletype.code'));
        $detail->addText('ArticleState_Code', $this->translate('articlestate.code'));
        $detail->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'));
        $detail->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $detail->addText('ArticleTranslation_Title', $this->translate('articletranslation.title'));
        $detail->addText('ArticleTranslation_Heading', $this->translate('articletranslation.heading'));
        $detail->addText('ArticleTranslation_SubHeading', $this->translate('articletranslation.subheading'));
        $detail->addText('ArticleTranslation_Teaser', $this->translate('articletranslation.teaser'));
        $detail->addText('ArticleTranslation_Text', $this->translate('articletranslation.text'));
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addText('Article_Code', $this->translate('article.code'));
        $edit->addSelect('ArticleType_Code', $this->translate('articletype.code'))
            ->setSelectOptions($this->getModel()->getArticleType_Options());
        $edit->addSelect('ArticleState_Code', $this->translate('articlestate.code'))
            ->setSelectOptions($this->getModel()->getArticleState_Options());
        $edit->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'));
        $edit->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $edit->addText('ArticleTranslation_Title', $this->translate('articletranslation.title'));
        $edit->addText('ArticleTranslation_Heading', $this->translate('articletranslation.heading'));
        $edit->addText('ArticleTranslation_SubHeading', $this->translate('articletranslation.subheading'));
        $edit->addTextarea('ArticleTranslation_Teaser', $this->translate('articletranslation.teaser'))
            ->setRows(3);
        $edit->addTextarea('ArticleTranslation_Text', $this->translate('articletranslation.text'))
            ->setRows(7);
        $edit->addSubmitAttribute('Locale_Code', $this->getTranslator()->getLocale());
    }

}
