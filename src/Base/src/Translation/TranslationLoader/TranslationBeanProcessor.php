<?php


namespace Base\Translation\TranslationLoader;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class TranslationBeanProcessor extends AbstractBeanProcessor
{
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('Translation_ID', 'Translation_ID', 'Translation', 'Translation_ID', true);
        $saver->addColumn('Translation_Code', 'Translation_Code', 'Translation', 'Translation_ID');
        $saver->addColumn('Translation_Namespace', 'Translation_Namespace', 'Translation', 'Translation_ID');
        $saver->addColumn('Locale_Code', 'Locale_Code', 'Translation', 'Translation_ID');
        $saver->addColumn('Translation_Text', 'Translation_Text', 'Translation', 'Translation_ID');
        parent::__construct($saver);
    }


    protected function validateForSave(BeanInterface $bean): bool
    {
        return true;
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
        return true;
    }

}
