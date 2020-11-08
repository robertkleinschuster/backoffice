<?php

namespace Pars\Base\Translation\TranslationLoader;

use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\Loader\RemoteLoaderInterface;
use Laminas\I18n\Translator\TextDomain;
use Niceshops\Bean\Finder\AbstractBeanFinder;

/**
 * Class TranslationBeanFinder
 * @package Pars\Base\Translation\TranslationLoader
 */
class TranslationBeanFinder extends AbstractBeanFinder implements RemoteLoaderInterface
{
    /**
     * TranslationBeanFinder constructor.
     * @param Adapter $adapter
     */
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

    /**
     * @param string $locale
     * @param string $textDomain
     * @return TextDomain|null
     */
    public function load($locale, $textDomain)
    {
        $data = [];
        try {
            $this->setLocale_Code($locale);
            $this->setTranslation_Namespace($textDomain);
            foreach ($this->getBeanListDecorator() as $bean) {
                $data[$bean->getData('Translation_Code')] = $bean->getData('Translation_Text');
            }
        } catch (\Exception $ex) {
        }
        return new TextDomain($data);
    }

    /**
     * @param int $translation_Id
     * @param bool $exclude
     * @return $this
     */
    public function setTranslation_ID(int $translation_Id, bool $exclude): self
    {
        if ($exclude) {
            $this->getBeanLoader()->excludeValue('Translation_ID', $translation_Id);
        } else {
            $this->getBeanLoader()->filterValue('Translation_ID', $translation_Id);
        }
        return $this;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale_Code(string $locale): self
    {
        $this->getBeanLoader()->filterValue('Locale_Code', $locale);
        return $this;
    }

    /**
     * @param string $namespace
     * @return $this
     */
    public function setTranslation_Namespace(string $namespace): self
    {
        $this->getBeanLoader()->filterValue('Translation_Namespace', $namespace);
        return $this;
    }
}
