<?php


namespace Base\File\Directory;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class FileDirectoryBeanProcessor extends AbstractBeanProcessor
{


    /**
     * FileDirectoryBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('FileDirectory_Code', 'FileDirectory_Code', 'FileDirectory', 'FileDirectory_Code', true);
        $saver->addColumn('FileDirectory_Name', 'FileDirectory_Name', 'FileDirectory', 'FileDirectory_Code');
        $saver->addColumn('FileDirectory_Active', 'FileDirectory_Active', 'FileDirectory', 'FileDirectory_Code');
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
