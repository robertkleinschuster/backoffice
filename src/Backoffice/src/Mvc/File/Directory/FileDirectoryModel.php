<?php

namespace Pars\Backoffice\Mvc\File\Directory;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\File\Directory\FileDirectoryBeanFinder;
use Pars\Base\File\Directory\FileDirectoryBeanProcessor;

class FileDirectoryModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new FileDirectoryBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new FileDirectoryBeanProcessor($this->getDbAdpater()));
    }
}
