<?php


namespace Base\Translation\TranslationLoader;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\Loader\RemoteLoaderInterface;
use Laminas\I18n\Translator\TextDomain;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class TranslationBeanFinder extends AbstractBeanFinder implements RemoteLoaderInterface
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('Translation_ID', 'Translation_ID', 'Translation', 'Translation_ID', true);
        $loader->addColumn('Translation_Code', 'Translation_Code', 'Translation', 'Translation_ID');
        $loader->addColumn('Translation_Namespace', 'Translation_Namespace', 'Translation', 'Translation_ID');
        $loader->addColumn('Locale_Code', 'Locale_Code', 'Translation', 'Translation_ID');
        $loader->addColumn('Translation_Text', 'Translation_Text', 'Translation', 'Translation_ID');
        parent::__construct($loader, new TranslationBeanFactory());
    }


    public function load($locale, $textDomain)
    {
        $data = [];
        try {
            $this->setLocale_Code($locale);
            $this->setTranslation_Namespace($textDomain);
            $this->find();
            foreach ($this->getBeanGenerator() as $bean) {
                $data[$bean->getData('Translation_Code')] = $bean->getData('Translation_Text');
            }
        } catch (\Exception $ex) {

        }
        return new TextDomain($data);
    }

    public function setTranslation_ID(int $translation_Id, bool $exclude): self
    {
        if ($exclude) {
            $this->getLoader()->excludeValue('Translation_ID', $translation_Id);
        } else {
            $this->getLoader()->filterValue('Translation_ID', $translation_Id);
        }
        return $this;
    }

    public function setLocale_Code(string $locale): self
    {
        $this->getLoader()->filterValue('Locale_Code', $locale);
        return $this;
    }

    public function setTranslation_Namespace(string $namespace): self
    {
        $this->getLoader()->filterValue('Translation_Namespace', $namespace);
        return $this;
    }

}
