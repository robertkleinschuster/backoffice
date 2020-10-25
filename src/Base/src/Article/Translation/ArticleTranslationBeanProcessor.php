<?php


namespace Base\Article\Translation;


use Base\Article\ArticleBeanProcessor;
use Base\Database\DatabaseBeanSaver;
use Cocur\Slugify\Slugify;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;

class ArticleTranslationBeanProcessor extends ArticleBeanProcessor
{

    /**
     * ArticleTranslationBeanProcessor constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
        $this->adapter = $adapter;
        $saver = $this->getSaver();
        if ($saver instanceof DatabaseBeanSaver) {
            $saver->addColumn('Article_ID', 'Article_ID', 'Article', 'Article_ID', true, null, ['ArticleTranslation']);
            $saver->addColumn('Locale_Code', 'Locale_Code', 'ArticleTranslation', 'Article_ID', true);
            $saver->addColumn('ArticleTranslation_Code', 'ArticleTranslation_Code', 'ArticleTranslation', 'Article_ID');
            $saver->addColumn('ArticleTranslation_Name', 'ArticleTranslation_Name', 'ArticleTranslation', 'Article_ID');
            $saver->addColumn('ArticleTranslation_Title', 'ArticleTranslation_Title', 'ArticleTranslation', 'Article_ID');
            $saver->addColumn('ArticleTranslation_Heading', 'ArticleTranslation_Heading', 'ArticleTranslation', 'Article_ID');
            $saver->addColumn('ArticleTranslation_SubHeading', 'ArticleTranslation_SubHeading', 'ArticleTranslation', 'Article_ID');
            $saver->addColumn('ArticleTranslation_Teaser', 'ArticleTranslation_Teaser', 'ArticleTranslation', 'Article_ID');
            $saver->addColumn('ArticleTranslation_Text', 'ArticleTranslation_Text', 'ArticleTranslation', 'Article_ID');
            $saver->addColumn('ArticleTranslation_Footer', 'ArticleTranslation_Footer', 'ArticleTranslation', 'Article_ID');
            $saver->addColumn('File_ID', 'File_ID', 'ArticleTranslation', 'File_ID', false, null, ['File'], 'ArticleTranslation');
        }
    }

    protected function beforeSave(BeanInterface $bean)
    {
        $slugify = new Slugify();
        if ($bean->hasData('ArticleTranslation_Code')) {
            $bean->setData('ArticleTranslation_Code', $slugify->slugify($bean->getData('ArticleTranslation_Code')));
        } elseif ($bean->hasData('ArticleTranslation_Name')) {
            $bean->setData('ArticleTranslation_Code', $slugify->slugify($bean->getData('ArticleTranslation_Name')));
        } elseif ($bean->hasData('ArticleTranslation_Title')) {
            $bean->setData('ArticleTranslation_Code', $slugify->slugify($bean->getData('ArticleTranslation_Title')));
        }
        parent::beforeSave($bean);
    }


    /**
     * @param BeanInterface $bean
     * @return bool
     */
    protected function validateForSave(BeanInterface $bean): bool
    {
        $parentResult = parent::validateForSave($bean);
        if (!$bean->hasData('Locale_Code') || !strlen(trim($bean->getData('Locale_Code')))) {
            $this->getValidationHelper()->addError('Locale_Code', $this->translate('locale.code.empty'));
        }
        if (!$bean->hasData('ArticleTranslation_Code') || !strlen(trim($bean->getData('ArticleTranslation_Code')))) {
            $this->getValidationHelper()->addError('ArticleTranslation_Code', $this->translate('articletranslation.code.empty'));
        }
        if (!$this->getValidationHelper()->hasError()) {
            $articleTranslationFinder = new ArticleTranslationBeanFinder($this->adapter);
            if ($bean->hasData('Article_ID')) {
                $articleTranslationFinder->setArticle_ID($bean->getData('Article_ID'), true);
            }
            $articleTranslationFinder->setLocale_Code($bean->getData('Locale_Code'));
            $articleTranslationFinder->setArticleTranslation_Code($bean->getData('ArticleTranslation_Code'));
            if ($articleTranslationFinder->count() > 0) {
                $this->getValidationHelper()->addError('ArticleTranslation_Code', $this->translate('articletranslation.code.unique'));
            }
        }
        if (!$bean->hasData('ArticleTranslation_Name') || !strlen(trim($bean->getData('ArticleTranslation_Name')))) {
            $this->getValidationHelper()->addError('ArticleTranslation_Name', $this->translate('articletranslation.name.empty'));
        }
        return $parentResult && !$this->getValidationHelper()->hasError();
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     */
    protected function validateForDelete(BeanInterface $bean): bool
    {
        return parent::validateForDelete($bean) && $bean->hasData('Article_ID');
    }


}
