<?php

namespace Pars\Backoffice\Mvc\Cms\SiteParagraph;

use Pars\Backoffice\Mvc\Base\BaseModel;
use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Cms\Paragraph\CmsParagraphBeanFinder;
use Pars\Base\Cms\SiteParagraph\CmsSiteParagraphBeanFinder;
use Pars\Base\Cms\SiteParagraph\CmsSiteParagraphBeanProcessor;

class CmsSiteParagraphModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsSiteParagraphBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsSiteParagraphBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }

    /**
     * @return array
     */
    public function getParagraph_Options()
    {
        $options = [];
        $finder = new CmsParagraphBeanFinder($this->getDbAdpater());

        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsParagraph_ID')] = $bean->getData('ArticleTranslation_Name');
        }
        return $options;
    }

}
