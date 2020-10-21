<?php


namespace Backoffice\Mvc\Cms\Post;


use Backoffice\Mvc\Base\BaseModel;
use Base\Cms\Post\State\CmsPostStateBeanFinder;
use Base\Cms\Post\Type\CmsPostTypeBeanFinder;
use Base\Cms\Post\CmsPostBeanFinder;
use Base\Cms\Post\CmsPostBeanProcessor;

class CmsPostModel extends BaseModel
{
    public function init()
    {
        $this->setFinder(new CmsPostBeanFinder($this->getDbAdpater()));
        $this->setProcessor(new CmsPostBeanProcessor($this->getDbAdpater()));
        $this->getFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }


    public function getCmsPostType_Options(): array
    {
        $options = [];
        $finder = new CmsPostTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsPostType_Active(true);
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('CmsPostType_Code')] = $bean->getData('CmsPostType_Code');
        }
        return $options;
    }

    public function getCmsPostState_Options(): array
    {
        $options = [];
        $finder = new CmsPostStateBeanFinder($this->getDbAdpater());
        $finder->setCmsPostState_Active(true);
        $finder->find();
        foreach ($finder->getBeanGenerator() as $bean) {
            $options[$bean->getData('CmsPostState_Code')] = $bean->getData('CmsPostState_Code');
        }
        return $options;
    }

}
