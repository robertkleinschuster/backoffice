<?php


namespace Base\File\Type;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class FileTypeBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('FileType_Code', 'FileType_Code', 'FileType', 'FileType_Code', true);
        $loader->addColumn('FileType_Name', 'FileType_Name', 'FileType', 'FileType_Code');
        $loader->addColumn('FileType_Mime', 'FileType_Mime', 'FileType', 'FileType_Code');
        $loader->addColumn('FileType_Active', 'FileType_Active', 'FileType', 'FileType_Code');
        parent::__construct($loader, new FileTypeBeanFactory());
    }

    public function setFileType_Active(bool $active): self
    {
        $this->getLoader()->filterValue('FileType_Active', $active);
        return $this;
    }
}
