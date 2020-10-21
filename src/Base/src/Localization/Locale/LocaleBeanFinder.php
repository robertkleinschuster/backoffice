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
        $loader->addColumn('Locale_UrlCode', 'Locale_UrlCode', 'Locale', 'Locale_Code');
        $loader->addColumn('Locale_Name', 'Locale_Name', 'Locale', 'Locale_Code');
        $loader->addColumn('Locale_Active', 'Locale_Active', 'Locale', 'Locale_Code');
        $loader->addColumn('Locale_Order', 'Locale_Order', 'Locale', 'Locale_Code');
        $loader->addOrder('Locale_Order');
        parent::__construct($loader, new LocaleBeanFactory());

    }

    public function setLocale_Order(int $order)
    {
        $this->getLoader()->filterValue('Locale_Order', $order);
        return $this;
    }

    public function setLocale_Active(bool $active): self
    {
        $this->getLoader()->filterValue('Locale_Active', $active);
        return $this;
    }

    public function setLocale_Code(string $code): self
    {
        $this->getLoader()->filterValue('Locale_Code', $code);
        return $this;
    }

    public function setLanguage(string $language): self
    {
        $this->getLoader()->addLike("$language%", 'Locale_Code');
        return $this;
    }

}
