<?php

namespace Pars\Backoffice\Mvc\Cms\Paragraph;

use Pars\Backoffice\Mvc\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Mvc\Parameter\IdParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Edit\Fields\Wysiwyg;
use Pars\Mvc\View\Components\Overview\Overview;

/**
 * Class CmsParagraphController
 * @package Pars\Backoffice\Mvc\Cms\Paragraph
 * @method CmsParagraphModel getModel()
 */
class CmsParagraphController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmsparagraph.create', 'cmsparagraph.edit', 'cmsparagraph.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmsparagraph');
    }


    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setId((new IdParameter())->addId('CmsParagraph_ID'));
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
        $detail->addText('CmsParagraphType_Code', $this->translate('cmsparagraphtype.code'))
            ->setChapter($this->translate('article.detail.general'))
            ->setAppendToColumnPrevious(true);
        $detail->addText('CmsParagraphState_Code', $this->translate('cmsparagraphstate.code'))
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
        $edit->addSelect('CmsParagraphType_Code', $this->translate('cmsparagraphtype.code'))
            ->setChapter($this->translate('article.edit.general'))
            ->setSelectOptions($this->getModel()->getCmsParagraphType_Options())
            ->setAppendToColumnPrevious(true);
        $edit->addSelect('CmsParagraphState_Code', $this->translate('cmsparagraphstate.code'))
            ->setChapter($this->translate('article.edit.general'))
            ->setSelectOptions($this->getModel()->getCmsParagraphState_Options())
            ->setAppendToColumnPrevious(true);
    }
}
