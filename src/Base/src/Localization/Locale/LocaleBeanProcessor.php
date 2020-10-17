<?php


namespace Base\Localization\Locale;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\BeanSaverInterface;

class LocaleBeanProcessor extends \NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor
{
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('Locale_Code', 'Locale_Code', 'Locale', 'Locale_Code', true);
        $saver->addColumn('Locale_Name', 'Locale_Name', 'Locale', 'Locale_Code');
        $saver->addColumn('Locale_Active', 'Locale_Active', 'Locale', 'Locale_Code');

        parent::__construct($saver);
    }


    /**
     * @inheritDoc
     */
    protected function validateForSave(BeanInterface $bean): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function validateForDelete(BeanInterface $bean): bool
    {
        return true;
    }

}
