<?php


namespace Backoffice\Mvc\File\Directory;

use Backoffice\Mvc\Base\BaseModel;
use Base\File\Directory\FileDirectoryBeanFinder;
use Base\File\Directory\FileDirectoryBeanProcessor;

class FileDirectoryModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new FileDirectoryBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new FileDirectoryBeanProcessor($this->getDbAdpater()));
    }

}
