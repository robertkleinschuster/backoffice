<?php


namespace Base\Localization\Locale;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;

class LocaleBeanFinder extends \NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('Locale_Code', 'Locale_Code', 'Locale', 'Locale_Code', true);
        $loader->addColumn('Locale_Name', 'Locale_Name', 'Locale', 'Locale_Code');
        $loader->addColumn('Locale_Active', 'Locale_Active', 'Locale', 'Locale_Code');
        parent::__construct($loader, new LocaleBeanFactory());

    }

    public function setLocale_Active(bool $active): self
    {
        $this->getLoader()->filterValue('Locale_Active', $active);
        return $this;
    }

}
