<?php


namespace Base\Cms\Site;


use Base\Article\Translation\ArticleTranslationBeanProcessor;
use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;

class CmsSiteBeanProcessor extends ArticleTranslationBeanProcessor
{

    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
        $saver = $this->getSaver();
        if ($saver instanceof DatabaseBeanSaver) {
            $saver->addColumn('Article_ID', 'Article_ID', 'Article', 'CmsSite_ID', true, null, ['ArticleTranslation', 'CmsSite']);
            $saver->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsSite', 'CmsSite_ID', true);
        }
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        return parent::validateForSave($bean);
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
        return parent::validateForDelete($bean) && $bean->hasData('CmsSite_ID') && $bean->hasData('Article_ID');
    }

}
