<?php


namespace Base\Cms\Site;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsSiteBeanProcessor extends AbstractBeanProcessor
{

    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('Article_ID', 'Article_ID', 'Article', 'CmsSite_ID', true, null, ['ArticleTranslation', 'CmsSite']);
        $saver->addColumn('Article_Code', 'Article_Code', 'Article', 'Article_ID');
        $saver->addColumn('ArticleState_Code', 'ArticleState_Code', 'Article', 'Article_ID');
        $saver->addColumn('ArticleType_Code', 'ArticleType_Code', 'Article', 'Article_ID');
        $saver->addColumn('Locale_Code', 'Locale_Code', 'ArticleTranslation', 'Article_ID', true);
        $saver->addColumn('ArticleTranslation_Code', 'ArticleTranslation_Code', 'ArticleTranslation', 'Article_ID');
        $saver->addColumn('ArticleTranslation_Name', 'ArticleTranslation_Name', 'ArticleTranslation', 'Article_ID');
        $saver->addColumn('ArticleTranslation_Title', 'ArticleTranslation_Title', 'ArticleTranslation', 'Article_ID');
        $saver->addColumn('ArticleTranslation_Heading', 'ArticleTranslation_Heading', 'ArticleTranslation', 'Article_ID');
        $saver->addColumn('ArticleTranslation_SubHeading', 'ArticleTranslation_SubHeading', 'ArticleTranslation', 'Article_ID');
        $saver->addColumn('ArticleTranslation_Teaser', 'ArticleTranslation_Teaser', 'ArticleTranslation', 'Article_ID');
        $saver->addColumn('ArticleTranslation_Text', 'ArticleTranslation_Text', 'ArticleTranslation', 'Article_ID');
        $saver->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsSite', 'CmsSite_ID', true);
        parent::__construct($saver);
    }

}
