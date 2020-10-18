<?php


namespace Base\Cms\Paragraph;


use Base\Article\Translation\ArticleTranslationBeanProcessor;
use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;

class CmsParagraphBeanProcessor extends ArticleTranslationBeanProcessor
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
        $saver = $this->getSaver();
        if ($saver instanceof DatabaseBeanSaver) {
            $saver->addColumn('Article_ID', 'Article_ID', 'Article', 'CmsParagraph_ID', true, null, ['ArticleTranslation', 'CmsParagraph']);
            $saver->addColumn('CmsParagraph_ID', 'CmsParagraph_ID', 'CmsParagraph', 'CmsParagraph_ID', true);
        }
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        return parent::validateForSave($bean);
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
        return parent::validateForDelete($bean) && $bean->hasData('CmsParagraph_ID') && $bean->hasData('Article_ID');
    }

}
