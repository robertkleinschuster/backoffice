<?php


namespace Base\Cms\SiteParagraph;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsSiteParagraphBeanProcessor extends AbstractBeanProcessor
{
    private $adapter;
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsSite_CmsParagraph', 'CmsSite_ID', true);
        $saver->addColumn('CmsParagraph_ID', 'CmsParagraph_ID', 'CmsSite_CmsParagraph', 'CmsParagraph_ID', true);
        $saver->addColumn('CmsSite_CmsParagraph_Order', 'CmsSite_CmsParagraph_Order', 'CmsSite_CmsParagraph', 'CmsParagraph_ID');
        parent::__construct($saver);
    }

    protected function beforeSave(BeanInterface $bean)
    {
        if (!$bean->hasData('CmsSite_CmsParagraph_Order') || $bean->getData('CmsSite_CmsParagraph_Order') === 0) {
            $order = 1;
            $finder = new CmsSiteParagraphBeanFinder($this->adapter);
            $finder->setCmsSite_ID($bean->getData('CmsSite_ID'));
            $finder->getLoader()->addOrder('CmsSite_CmsParagraph_Order', true);
            $finder->limit(1, 0);
            if ($finder->find() == 1) {
                $lastBean = $finder->getBean();
                if ($lastBean->hasData('CmsSite_CmsParagraph_Order')) {
                    $order = $lastBean->getData('CmsSite_CmsParagraph_Order') + 1;
                }
            }
            $bean->setData('CmsSite_CmsParagraph_Order', $order);
        }
        parent::beforeSave($bean);
    }
}
