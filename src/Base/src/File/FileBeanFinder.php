<?php


namespace Base\File;


use Base\Article\Translation\ArticleTranslationBeanFinder;
use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;


class FileBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('File_ID', 'File_ID', 'File', 'File_ID', true);
        $loader->addColumn('File_Name', 'File_Name', 'File', 'File_ID');
        $loader->addColumn('File_Code', 'File_Code', 'File', 'File_ID');
        $loader->addColumn('FileType_Code', 'FileType_Code', 'File', 'File_ID', false, null, ['FileType']);
        $loader->addColumn('FileDirectory_ID', 'FileDirectory_ID', 'File', 'FileDirectory_ID', false, null, ['FileDirectory']);
        $loader->addColumn('FileDirectory_Code', 'FileDirectory_Code', 'FileDirectory', 'FileDirectory_ID');
        $loader->addColumn('FileDirectory_Name', 'FileDirectory_Name', 'FileDirectory', 'FileDirectory_ID');
        $loader->addColumn('FileType_Mime', 'FileType_Mime', 'FileType', 'FileType_Code');
        $loader->addColumn('FileType_Name', 'FileType_Name', 'FileType', 'FileType_Code');
        parent::__construct($loader, new FileBeanFactory());
    }


    public function setFileType_Code(string $type): self
    {
        $this->getLoader()->filterValue('FileType_Code', $type);
        return $this;
    }

    public function setFileDirectory_Code(string $type): self
    {
        $this->getLoader()->filterValue('FileDirectory_Code', $type);
        return $this;
    }

}
