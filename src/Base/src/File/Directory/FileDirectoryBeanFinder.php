<?php


namespace Base\File\Directory;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

class FileDirectoryBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('FileDirectory_Code', 'FileDirectory_Code', 'FileDirectory', 'FileDirectory_Code', true);
        $loader->addColumn('FileDirectory_Name', 'FileDirectory_Name', 'FileDirectory', 'FileDirectory_Code');
        $loader->addColumn('FileDirectory_Active', 'FileDirectory_Active', 'FileDirectory', 'FileDirectory_Code');
        parent::__construct($loader, new FileDirectoryBeanFactory());
    }

    public function setFileDirectory_Active(bool $active): self
    {
        $this->getLoader()->filterValue('FileDirectory_Active', $active);
        return $this;
    }

}
