<?php


namespace Backoffice\Mvc\File;

use Backoffice\Mvc\Base\BaseModel;
use Base\File\Directory\FileDirectoryBeanFinder;
use Base\File\FileBeanFinder;
use Base\File\FileBeanProcessor;
use Base\File\Type\FileTypeBeanFinder;

class FileModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new FileBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new FileBeanProcessor($this->getDbAdpater()));
    }

    public function getFileType_Options()
    {
        $options = [];
        $finder = new FileTypeBeanFinder($this->getDbAdpater());
        $finder->setFileType_Active(true);
        $finder->find();
        $beanList = $finder->getBeanList();
        foreach ($beanList as $bean) {
            $options[$bean->getData('FileType_Code')] = $bean->getData('FileType_Name');
        }
        return $options;
    }

    public function getFileDirectory_Options()
    {
        $options = [];
        $finder = new FileDirectoryBeanFinder($this->getDbAdpater());
        $finder->setFileDirectory_Active(true);
        $finder->find();
        $beanList = $finder->getBeanList();
        foreach ($beanList as $bean) {
            $options[$bean->getData('FileDirectory_ID')] = $bean->getData('FileDirectory_Name');
        }
        return $options;
    }

}
