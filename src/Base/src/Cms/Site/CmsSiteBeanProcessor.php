<?php

namespace Pars\Base\Cms\Site;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Base\Article\Translation\ArticleTranslationBeanProcessor;
use Laminas\Db\Adapter\Adapter;
use Pars\Base\Database\DatabaseBeanSaver;

/**
 * Class CmsSiteBeanProcessor
 * @package Pars\Base\Cms\Site
 */
class CmsSiteBeanProcessor extends ArticleTranslationBeanProcessor
{

    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
        $saver = $this->getBeanSaver();
        if ($saver instanceof DatabaseBeanSaver) {
            $saver->addColumn('Article_ID', 'Article_ID', 'Article', 'CmsSite_ID', true, null, ['ArticleTranslation', 'CmsSite']);
            $saver->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsSite', 'CmsSite_ID', true);
            $saver->addColumn('CmsSiteType_Code', 'CmsSiteType_Code', 'CmsSite', 'CmsSite_ID');
            $saver->addColumn('CmsSiteState_Code', 'CmsSiteState_Code', 'CmsSite', 'CmsSite_ID');
        }
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        if (!$bean->hasData('CmsSiteState_Code') || !strlen(trim(($bean->getData('CmsSiteState_Code'))))) {
            $this->getValidationHelper()->addError('CmsSiteState_Code', $this->translate('articlestate.code.empty'));
        }
        if (!$bean->hasData('CmsSiteType_Code') || !strlen(trim(($bean->getData('CmsSiteType_Code'))))) {
            $this->getValidationHelper()->addError('CmsSiteType_Code', $this->translate('articletype.code.empty'));
        }
        return parent::validateForSave($bean);
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
        return parent::validateForDelete($bean) && $bean->hasData('CmsSite_ID') && $bean->hasData('Article_ID');
    }
}
