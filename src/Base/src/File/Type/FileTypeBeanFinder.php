<?php

namespace Pars\Base\File\Type;

use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Finder\AbstractBeanFinder;

/**
 * Class FileTypeBeanFinder
 * @package Pars\Base\File\Type
 */
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
        $this->getBeanLoader()->filterValue('FileType_Active', $active);
        return $this;
    }

    public function setFileType_Code(string $code): self
    {
        $this->getBeanLoader()->filterValue('FileType_Code', $code);
        return $this;
    }
}
