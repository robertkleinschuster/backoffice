<?php


namespace Base\Cms\Post;


use Base\Article\Translation\ArticleTranslationBeanProcessor;
use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;

class CmsPostBeanProcessor extends ArticleTranslationBeanProcessor
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
        $saver = $this->getSaver();
        if ($saver instanceof DatabaseBeanSaver) {
            $saver->addColumn('Article_ID', 'Article_ID', 'Article', 'CmsPost_ID', true, null, ['ArticleTranslation', 'CmsPost']);
            $saver->addColumn('CmsPost_ID', 'CmsPost_ID', 'CmsPost', 'CmsPost_ID', true);
            $saver->addColumn('CmsPostType_Code', 'CmsPostType_Code', 'CmsPost', 'CmsPost_ID');
            $saver->addColumn('CmsPostState_Code', 'CmsPostState_Code', 'CmsPost', 'CmsPost_ID');
        }
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        if (!$bean->hasData('CmsPostState_Code') || !strlen(trim(($bean->getData('CmsPostState_Code'))))) {
            $this->getValidationHelper()->addError('CmsPostState_Code', $this->translate('articlestate.code.empty'));
        }
        if (!$bean->hasData('CmsPostType_Code') || !strlen(trim(($bean->getData('CmsPostType_Code'))))) {
            $this->getValidationHelper()->addError('CmsPostType_Code', $this->translate('articletype.code.empty'));
        }
        return parent::validateForSave($bean);
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
        return parent::validateForDelete($bean) && $bean->hasData('CmsPost_ID') && $bean->hasData('Article_ID');
    }

}
