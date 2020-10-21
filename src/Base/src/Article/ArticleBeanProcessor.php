<?php


namespace Base\Article;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Mvc\Helper\ValidationHelperAwareInterface;
use Mvc\Helper\ValidationHelperAwareTrait;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class ArticleBeanProcessor extends AbstractBeanProcessor implements ValidationHelperAwareInterface, TranslatorAwareInterface
{
    use ValidationHelperAwareTrait;
    use TranslatorAwareTrait;

    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('Article_ID', 'Article_ID', 'Article', 'Article_ID', true);
        $saver->addColumn('Article_Code', 'Article_Code', 'Article', 'Article_ID');
        parent::__construct($saver);
    }

    protected function translate(string $name): string
    {
        return $this->getTranslator()->translate($name, 'validation');
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        if (!$bean->hasData('Article_Code') || !strlen(trim(($bean->getData('Article_Code'))))) {
            $this->getValidationHelper()->addError('Article_Code', $this->translate('article.code.empty'));
        } else {
            $articleFinder = new ArticleBeanFinder($this->adapter);
            if ($bean->hasData('Article_ID')) {
                $articleFinder->setArticle_ID($bean->getData('Article_ID'), true);
            }
            $articleFinder->setArticle_Code($bean->getData('Article_Code'));
            if ($articleFinder->count() > 0) {
                $this->getValidationHelper()->addError('Article_Code', $this->translate('article.code.unique'));
            }
        }

        return !$this->getValidationHelper()->hasError();
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     */
    protected function validateForDelete(BeanInterface $bean): bool
    {
        return $bean->hasData('Article_ID');
    }


}
