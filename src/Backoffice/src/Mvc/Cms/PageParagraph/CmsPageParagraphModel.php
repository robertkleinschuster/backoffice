<?php

namespace Pars\Backoffice\Mvc\Cms\PageParagraph;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Cms\Paragraph\CmsParagraphBeanFinder;
use Pars\Base\Cms\PageParagraph\CmsPageParagraphBeanFinder;
use Pars\Base\Cms\PageParagraph\CmsPageParagraphBeanProcessor;

class CmsPageParagraphModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsPageParagraphBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsPageParagraphBeanProcessor($this->getDbAdpater()));
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
