<?php

namespace Pars\Base\Cms\Paragraph;

use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Base\Article\Translation\ArticleTranslationBeanProcessor;
use Pars\Base\Database\DatabaseBeanSaver;

class CmsParagraphBeanProcessor extends ArticleTranslationBeanProcessor
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
        $saver = $this->getBeanSaver();
        if ($saver instanceof DatabaseBeanSaver) {
            $saver->addColumn('Article_ID', 'Article_ID', 'Article', 'CmsParagraph_ID', true, null, ['ArticleTranslation', 'CmsParagraph']);
            $saver->addColumn('CmsParagraph_ID', 'CmsParagraph_ID', 'CmsParagraph', 'CmsParagraph_ID', true);
            $saver->addColumn('CmsParagraphType_Code', 'CmsParagraphType_Code', 'CmsParagraph', 'CmsParagraph_ID');
            $saver->addColumn('CmsParagraphState_Code', 'CmsParagraphState_Code', 'CmsParagraph', 'CmsParagraph_ID');
        }
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        if (!$bean->hasData('CmsParagraphState_Code') || !strlen(trim(($bean->getData('CmsParagraphState_Code'))))) {
            $this->getValidationHelper()->addError('CmsParagraphState_Code', $this->translate('articlestate.code.empty'));
        }
        if (!$bean->hasData('CmsParagraphType_Code') || !strlen(trim(($bean->getData('CmsParagraphType_Code'))))) {
            $this->getValidationHelper()->addError('CmsParagraphType_Code', $this->translate('articletype.code.empty'));
        }
        return parent::validateForSave($bean);
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
        return parent::validateForDelete($bean) && $bean->hasData('CmsParagraph_ID') && $bean->hasData('Article_ID');
    }
}
