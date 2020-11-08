<?php

namespace Pars\Backoffice\Mvc\Cms\Site;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Backoffice\Mvc\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Mvc\Parameter\IdParameter;
use Pars\Mvc\Parameter\MoveParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Detail\Fields\Link;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Edit\Fields\Wysiwyg;
use Pars\Mvc\View\Components\Overview\Overview;
use Pars\Mvc\View\Components\Toolbar\Toolbar;

/**
 * Class CmsSiteController
 * @package Pars\Backoffice\Mvc\Cms\Site
 * @method CmsSiteModel getModel()
 */
class CmsSiteController extends CrudController
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


    public function detailAction()
    {
        $bean = $this->getModel()->getBean();

        $toolbar = new Toolbar();
        $toolbar->addButton('{ArticleTranslation_Code}', $this->translate('site.toolbar.frontend'))
            ->setTarget(Link::TARGET_BLANK)->setStyle(Link::STYLE_INFO);
        $toolbar->setBean($bean);
        $this->getView()->addComponent($toolbar);

        $detail = $this->initDetailTemplate();
        $detail->setBean($bean);

        $toolbar = new Toolbar($this->translate('cmssiteparagraph.overview.title'));
        $toolbar->addButton($this->getPathHelper()
            ->setController('cmssiteparagraph')
            ->setAction('create')
            ->setId($this->getControllerRequest()->getId())
            ->getPath(), $this->translate('cmssiteparagraph.create'));
        $this->getView()->addComponent($toolbar);
        $overview = new Overview();
        $overview->addDetailIcon(
            $this->getPathHelper()
                ->setController('cmsparagraph')
                ->setAction('detail')
                ->setId((new IdParameter())->addId('CmsParagraph_ID'))
                ->getPath()
        )->setWidth(122);
        $overview->addEditIcon(
            $this->getPathHelper()
                ->setController('cmsparagraph')
                ->setAction('edit')
                ->setId((new IdParameter())->addId('CmsParagraph_ID'))
                ->getPath()
        );
        $overview->addDeleteIcon(
            $this->getPathHelper()
                ->setController('cmssiteparagraph')
                ->setAction('delete')
                ->setId((new IdParameter())->addId('CmsParagraph_ID')->addId('CmsSite_ID'))
                ->getPath()
        );

        $beanList = $bean->getData('CmsParagraph_BeanList')->toBeanList();

        $overview->addMoveDownIcon($this->getPathHelper()
            ->setController('cmssiteparagraph')
            ->setController('index')
            ->setId((new IdParameter())->addId('CmsParagraph_ID')->addId('CmsSite_ID'))
            ->addParameter((new MoveParameter())
                ->setDown('CmsSite_CmsParagraph_Order')
                ->setReferenceField('CmsSite_ID')
                ->setReferenceValue('{CmsSite_ID}')))->setShow(function (BeanInterface $b) use ($bean, $beanList) {
                    return $b->hasData('CmsSite_CmsParagraph_Order') && $b->getData('CmsSite_CmsParagraph_Order') < $beanList->count();
                });

        $overview->addMoveUpIcon($this->getPathHelper()
            ->setController('cmssiteparagraph')
            ->setController('index')
            ->setId((new IdParameter())->addId('CmsParagraph_ID')->addId('CmsSite_ID'))
            ->addParameter((new MoveParameter())
                ->setUp('CmsSite_CmsParagraph_Order')
                ->setReferenceField('CmsSite_ID')
                ->setReferenceValue('{CmsSite_ID}')))->setShow(function (BeanInterface $b) {
                    return $b->hasData('CmsSite_CmsParagraph_Order') && $b->getData('CmsSite_CmsParagraph_Order') > 1;
                });


        $overview->addText('Article_Code', $this->translate('article.code'))->setWidth(150);
        $overview->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));

        $overview->setBeanList($beanList);
        $this->getView()->addComponent($overview);
    }

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setId((new IdParameter())->addId('CmsSite_ID'));
    }

    protected function addOverviewFields(Overview $overview): void
    {
        $overview->addText('Article_Code', $this->translate('article.code'))->setWidth(150);
        $overview->addText('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $overview->addText('ArticleTranslation_Code', $this->translate('articletranslation.code'));
    }

    protected function addDetailFields(Detail $detail): void
    {

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
