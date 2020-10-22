<?php


namespace Base\File;


use Base\Article\Translation\ArticleTranslationBeanFinder;
use Laminas\Db\Adapter\Adapter;



class FileBeanFinder extends ArticleTranslationBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter, new FileBeanFactory());
        $loader = $this->getLoader();
        $loader->resetDbInfo();
        $loader->addColumn('File_ID', 'File_ID', 'File', 'File_ID', true);
        $loader->addColumn('File_Name', 'File_Name', 'File', 'File_ID');
        $loader->addColumn('FileType_Code', 'FileType_Code', 'File', 'File_ID');
        $loader->addColumn('FileDirectory_Code', 'FileDirectory_Code', 'File', 'File_ID');
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
