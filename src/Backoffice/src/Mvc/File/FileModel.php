<?php

namespace Pars\Backoffice\Mvc\File;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\File\Directory\FileDirectoryBeanFinder;
use Pars\Base\File\FileBeanFinder;
use Pars\Base\File\FileBeanProcessor;
use Pars\Base\File\Type\FileTypeBeanFinder;

class FileModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new FileBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new FileBeanProcessor($this->getDbAdpater()));
    }

    public function getFileType_Options()
    {
        $options = [];
        $finder = new FileTypeBeanFinder($this->getDbAdpater());
        $finder->setFileType_Active(true);

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

        $beanList = $finder->getBeanList();
        foreach ($beanList as $bean) {
            $options[$bean->getData('FileDirectory_ID')] = $bean->getData('FileDirectory_Name');
        }
        return $options;
    }
}
