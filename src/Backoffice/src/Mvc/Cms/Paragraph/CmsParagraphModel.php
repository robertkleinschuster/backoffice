<?php


namespace Backoffice\Mvc\Cms\Paragraph;


use Backoffice\Mvc\Base\BaseModel;
use Base\Cms\Paragraph\CmsParagraphBeanFinder;
use Base\Cms\Paragraph\CmsParagraphBeanProcessor;

class CmsParagraphModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new CmsParagraphBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new CmsParagraphBeanProcessor($this->getDbAdpater()));
    }

}
