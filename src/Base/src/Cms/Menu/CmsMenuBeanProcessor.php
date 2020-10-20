<?php


namespace Base\Cms\Menu;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsMenuBeanProcessor extends AbstractBeanProcessor
{
    private $adapter;
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsMenu_ID', 'CmsMenu_ID', 'CmsMenu', 'CmsMenu_ID', true);
        $saver->addColumn('CmsMenu_ID_Parent', 'CmsMenu_ID_Parent', 'CmsMenu', 'CmsMenu_ID');
        $saver->addColumn('CmsSite_ID', 'CmsSite_ID', 'CmsMenu', 'CmsMenu_ID');
        $saver->addColumn('CmsMenu_Order', 'CmsMenu_Order', 'CmsMenu', 'CmsMenu_ID');
        parent::__construct($saver);
    }

    protected function beforeSave(BeanInterface $bean)
    {
        if (!$bean->hasData('CmsMenu_Order') || $bean->getData('CmsMenu_Order') === 0) {
            $order = 1;
            $finder = new CmsMenuBeanFinder($this->adapter);
            $finder->getLoader()->addOrder('CmsMenu_Order', true);
            $finder->limit(1, 0);
            if ($finder->find() == 1) {
                $lastBean = $finder->getBean();
                if ($lastBean->hasData('CmsMenu_Order')) {
                    $order = $lastBean->getData('CmsMenu_Order') + 1;
                }
            }
            $bean->setData('CmsMenu_Order', $order);
        }
        parent::beforeSave($bean);
    }


}
