<?php

namespace Pars\Base\File\Directory;

use Pars\Base\Database\DatabaseBeanLoader;
use Pars\Base\File\FileBeanFinder;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Finder\AbstractBeanFinder;

/**
 * Class FileDirectoryBeanFinder
 * @package Pars\Base\File\Directory
 */
class FileDirectoryBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('FileDirectory_ID', 'FileDirectory_ID', 'FileDirectory', 'FileDirectory_ID', true);
        $loader->addColumn('FileDirectory_Code', 'FileDirectory_Code', 'FileDirectory', 'FileDirectory_ID');
        $loader->addColumn('FileDirectory_Name', 'FileDirectory_Name', 'FileDirectory', 'FileDirectory_ID');
        $loader->addColumn('FileDirectory_Active', 'FileDirectory_Active', 'FileDirectory', 'FileDirectory_ID');
        parent::__construct($loader, new FileDirectoryBeanFactory());
        $this->addLinkedFinder(new FileBeanFinder($adapter), 'File_BeanList', 'FileDirectory_ID', 'FileDirectory_ID');
    }

    public function setFileDirectory_Active(bool $active): self
    {
        $this->getBeanLoader()->filterValue('FileDirectory_Active', $active);
        return $this;
    }


    public function setFileDirectory_ID(int $id): self
    {
        $this->getBeanLoader()->filterValue('FileDirectory_ID', $id);
        return $this;
    }
}
