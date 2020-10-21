<?php


namespace Backoffice\Mvc\Cms\Site;


use Backoffice\Mvc\Base\BackofficeBeanFormatter;
use Mvc\Helper\PathHelper;
use Mvc\View\ComponentDataBean;
use Mvc\View\Components\Detail\Detail;
use Mvc\View\Components\Detail\Fields\Link;
use Mvc\View\Components\Edit\Edit;
use Mvc\View\Components\Edit\Fields\File;
use Mvc\View\Components\Edit\Fields\Wysiwyg;
use Mvc\View\Components\Overview\Overview;
use Mvc\View\Components\Toolbar\Toolbar;
use NiceshopsDev\Bean\BeanInterface;

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
        $bean = $this->getModel()->getFinder()->getBean();

        $toolbar = new Toolbar();
        $toolbar->addButton('{ArticleTranslation_Code}', $this->translate('site.toolbar.frontend'))
            ->setTarget(Link::TARGET_BLANK)->setStyle(Link::STYLE_INFO);
        $toolbar->addBean($bean);
        $this->getView()->addComponent($toolbar);

        $detail = $this->initDetailTemplate();
        $detail->setBean($bean);

        $toolbar = new Toolbar($this->translate('cmssiteparagraph.overview.title'));
        $toolbar->addButton($this->getPathHelper()->setController('cmssiteparagraph')->setAction('create')->setViewIdMap($this->getControllerRequest()->getViewIdMap())->getPath(), $this->translate('cmssiteparagraph.create'));
        $toolbar->addBean(new ComponentDataBean());
        $this->getView()->addComponent($toolbar);
        $overview = new Overview();
        $overview->addDetailIcon($this->getPathHelper()->setController('cmsparagraph')->setAction('detail')->setViewIdMap(['CmsParagraph_ID' => '{CmsParagraph_ID}'])->getPath())->setWidth(122);
        $overview->addEditIcon($this->getPathHelper()->setController('cmsparagraph')->setAction('edit')->setViewIdMap(['CmsParagraph_ID' => '{CmsParagraph_ID}'])->getPath());
        $overview->addDeleteIcon($this->getPathHelper()->setController('cmssiteparagraph')->setAction('delete')->setViewIdMap(['CmsParagraph_ID' => '{CmsParagraph_ID}', 'CmsSite_ID' => '{CmsSite_ID}'])->getPath());

        $overview->addLink('', '')
            ->setLink($this->getDetailPath()->setController('cmssiteparagraph')->setAction('order')->setParams(['order' => 'up'])->setViewIdMap(['CmsParagraph_ID' => '{CmsParagraph_ID}', 'CmsSite_ID' => '{CmsSite_ID}'])->getPath())
            ->setValue('')
            ->setIcon(Link::ICON_ARROW_UP)
            ->setStyle(Link::STYLE_INFO)
            ->setOutline(true)
            ->addOption(Link::OPTION_TEXT_DECORATION_NONE)
            ->addOption(Link::OPTION_BUTTON_STYLE)
            ->setSize(Link::SIZE_SMALL)
            ->setChapter('order')
            ->setWidth(85)
            ->setShow(function (BeanInterface $b) {
                return $b->hasData('CmsSite_CmsParagraph_Order') && $b->getData('CmsSite_CmsParagraph_Order') > 1;

            });
        $beanList = $bean->getData('CmsParagraph_BeanList')->toBeanList();
        $overview->addLink('', '')
            ->setLink($this->getDetailPath()->setController('cmssiteparagraph')->setAction('order')->setParams(['order' => 'down'])->setViewIdMap(['CmsParagraph_ID' => '{CmsParagraph_ID}', 'CmsSite_ID' => '{CmsSite_ID}'])->getPath())
            ->setValue('')
            ->setIcon(Link::ICON_ARROW_DOWN)
            ->setStyle(Link::STYLE_INFO)
            ->setOutline(true)
            ->addOption(Link::OPTION_TEXT_DECORATION_NONE)
            ->addOption(Link::OPTION_BUTTON_STYLE)
            ->setSize(Link::SIZE_SMALL)
            ->setChapter('order')->setShow(function (BeanInterface $b) use($bean, $beanList){
                return $b->hasData('CmsSite_CmsParagraph_Order') && $b->getData('CmsSite_CmsParagraph_Order') < $beanList->count();
            });

        $overview->addText('Article_Code', $this->translate('article.code'))->setWidth(150);
        $overview->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));

        $overview->setBeanList($beanList);
        $this->getView()->addComponent($overview);

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
        $overview->addText('Article_Code', $this->translate('article.code'))->setWidth(150);
        $overview->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $overview->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'));

    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);

        $detail->setCols(2);
        $detail->addText('ArticleTranslation_Title', $this->translate('articletranslation.title'))
            ->setChapter($this->translate('article.detail.content'));
        $detail->addText('ArticleTranslation_Heading', $this->translate('articletranslation.heading'))
            ->setChapter($this->translate('article.detail.content'))
            ->setAppendToColumnPrevious(true);
        $detail->addText('ArticleTranslation_SubHeading', $this->translate('articletranslation.subheading'))
            ->setChapter($this->translate('article.detail.content'))
            ->setAppendToColumnPrevious(true);
        $detail->addText('ArticleTranslation_Teaser', $this->translate('articletranslation.teaser'))
            ->setChapter($this->translate('article.detail.content'))
            ->setAppendToColumnPrevious(true);
        $detail->addText('ArticleTranslation_Text', $this->translate('articletranslation.text'))
            ->setChapter($this->translate('article.detail.content'))
            ->setAppendToColumnPrevious(true);

        $detail->addText('ArticleTranslation_Footer', $this->translate('articletranslation.footer'))
            ->setChapter($this->translate('article.detail.content'))
            ->setAppendToColumnPrevious(true);


        $detail->addText('Article_Code', $this->translate('article.code'))
            ->setChapter($this->translate('article.detail.general'));
        $detail->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'))
            ->setChapter($this->translate('article.detail.general'))
            ->setAppendToColumnPrevious(true);
        $detail->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'))
            ->setChapter($this->translate('article.detail.general'))
            ->setAppendToColumnPrevious(true);
        $detail->addText('CmsSiteType_Code', $this->translate('cmssitetype.code'))
            ->setChapter($this->translate('article.detail.general'))
            ->setAppendToColumnPrevious(true);
        $detail->addText('CmsSiteState_Code', $this->translate('cmssitestate.code'))
            ->setChapter($this->translate('article.detail.general'))
            ->setAppendToColumnPrevious(true);
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->setCols(2);
        $edit->addText('ArticleTranslation_Title', $this->translate('articletranslation.title'))
            ->setChapter($this->translate('article.edit.content'));
        $edit->addText('ArticleTranslation_Heading', $this->translate('articletranslation.heading'))
            ->setChapter($this->translate('article.edit.content'))
            ->setAppendToColumnPrevious(true);
        $edit->addText('ArticleTranslation_SubHeading', $this->translate('articletranslation.subheading'))
            ->setChapter($this->translate('article.edit.content'))
            ->setAppendToColumnPrevious(true);
        $edit->addWysiwyg('ArticleTranslation_Teaser', $this->translate('articletranslation.teaser'))
            ->setChapter($this->translate('article.edit.content'))
            ->setRows(5)->setType(Wysiwyg::TYPE_TOOLTIP)
            ->setAppendToColumnPrevious(true);
        $edit->addWysiwyg('ArticleTranslation_Text', $this->translate('articletranslation.text'))
            ->setChapter($this->translate('article.edit.content'))
            ->setRows(5)
            ->setAppendToColumnPrevious(true);
        $edit->addWysiwyg('ArticleTranslation_Footer', $this->translate('articletranslation.footer'))
            ->setChapter($this->translate('article.edit.content'))
            ->setRows(5)
            ->setAppendToColumnPrevious(true);

        $edit->addSubmitAttribute('Locale_Code', $this->getTranslator()->getLocale())->setAppendToColumnPrevious(true);

        $edit->addText('Article_Code', $this->translate('article.code'))
            ->setChapter($this->translate('article.edit.general'));
        $edit->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'))
            ->setChapter($this->translate('article.edit.general'))
            ->setAppendToColumnPrevious(true);
        $edit->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'))
            ->setChapter($this->translate('article.edit.general'))
            ->setAppendToColumnPrevious(true);
        $edit->addSelect('CmsSiteType_Code', $this->translate('cmssitetype.code'))
            ->setChapter($this->translate('article.edit.general'))
            ->setSelectOptions($this->getModel()->getCmsSiteType_Options())
            ->setAppendToColumnPrevious(true);
        $edit->addSelect('CmsSiteState_Code', $this->translate('cmssitestate.code'))
            ->setChapter($this->translate('article.edit.general'))
            ->setSelectOptions($this->getModel()->getCmsSiteState_Options())
            ->setAppendToColumnPrevious(true);
    }

}
