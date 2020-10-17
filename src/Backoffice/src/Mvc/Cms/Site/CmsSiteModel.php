<?php


namespace Backoffice\Mvc\Cms\Site;


use Base\Cms\Site\CmsSiteBeanFinder;
use Base\Cms\Site\CmsSiteBeanProcessor;

class CmsSiteModel extends \Backoffice\Mvc\Base\BaseModel
{

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->setFinder(new CmsSiteBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new CmsSiteBeanProcessor($this->getDbAdpater()));
    }
}
