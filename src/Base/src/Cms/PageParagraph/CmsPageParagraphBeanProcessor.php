<?php

namespace Pars\Base\Cms\PageParagraph;

use Pars\Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Processor\AbstractBeanProcessor;

/**
 * Class CmsPageParagraphBeanProcessor
 * @package Pars\Base\Cms\PageParagraph
 */
class CmsPageParagraphBeanProcessor extends AbstractBeanProcessor
{
    private $adapter;
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsPage_ID', 'CmsPage_ID', 'CmsPage_CmsParagraph', 'CmsPage_ID', true);
        $saver->addColumn('CmsParagraph_ID', 'CmsParagraph_ID', 'CmsPage_CmsParagraph', 'CmsParagraph_ID', true);
        $saver->addColumn('CmsPage_CmsParagraph_Order', 'CmsPage_CmsParagraph_Order', 'CmsPage_CmsParagraph', 'CmsParagraph_ID');
        parent::__construct($saver);
    }

    protected function beforeSave(BeanInterface $bean)
    {
        if (!$bean->hasData('CmsPage_CmsParagraph_Order') || $bean->getData('CmsPage_CmsParagraph_Order') === 0) {
            $order = 1;
            $finder = new CmsPageParagraphBeanFinder($this->adapter);
            $finder->setCmsPage_ID($bean->getData('CmsPage_ID'));
            $finder->getBeanLoader()->addOrder('CmsPage_CmsParagraph_Order', true);
            $finder->limit(1, 0);
            if ($finder->find() == 1) {
                $lastBean = $finder->getBean();
                if ($lastBean->hasData('CmsPage_CmsParagraph_Order')) {
                    $order = $lastBean->getData('CmsPage_CmsParagraph_Order') + 1;
                }
            }
            $bean->setData('CmsPage_CmsParagraph_Order', $order);
        }
        parent::beforeSave($bean);
    }
}
