<?php

namespace Pars\Backoffice\Mvc\Cms\Post;

use Pars\Backoffice\Mvc\Base\CrudModel;
use Pars\Base\Cms\Post\State\CmsPostStateBeanFinder;
use Pars\Base\Cms\Post\Type\CmsPostTypeBeanFinder;
use Pars\Base\Cms\Post\CmsPostBeanFinder;
use Pars\Base\Cms\Post\CmsPostBeanProcessor;

class CmsPostModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsPostBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsPostBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }

    public function getCmsPostType_Options(): array
    {
        $options = [];
        $finder = new CmsPostTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsPostType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsPostType_Code')] = $bean->getData('CmsPostType_Code');
        }
        return $options;
    }

    public function getCmsPostState_Options(): array
    {
        $options = [];
        $finder = new CmsPostStateBeanFinder($this->getDbAdpater());
        $finder->setCmsPostState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsPostState_Code')] = $bean->getData('CmsPostState_Code');
        }
        return $options;
    }
}
