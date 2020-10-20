<?php


namespace Base\Localization\Locale;


use Base\Cms\Menu\CmsMenuBeanFinder;
use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\BeanSaverInterface;

class LocaleBeanProcessor extends \NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('Locale_Code', 'Locale_Code', 'Locale', 'Locale_Code', true);
        $saver->addColumn('Locale_Name', 'Locale_Name', 'Locale', 'Locale_Code');
        $saver->addColumn('Locale_Active', 'Locale_Active', 'Locale', 'Locale_Code');
        $saver->addColumn('Locale_Order', 'Locale_Order', 'Locale', 'Locale_Code');
        parent::__construct($saver);
    }

    protected function beforeSave(BeanInterface $bean)
    {
        if (!$bean->hasData('Locale_Order') || $bean->getData('Locale_Order') === 0) {
            $order = 1;
            $finder = new LocaleBeanFinder($this->adapter);
            $finder->getLoader()->addOrder('Locale_Order', true);
            $finder->limit(1, 0);
            if ($finder->find() == 1) {
                $lastBean = $finder->getBean();
                if ($lastBean->hasData('Locale_Order')) {
                    $order = $lastBean->getData('Locale_Order') + 1;
                }
            }
            $bean->setData('Locale_Order', $order);
        }
        parent::beforeSave($bean);
    }

    /**
     * @inheritDoc
     */
    protected function validateForSave(BeanInterface $bean): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function validateForDelete(BeanInterface $bean): bool
    {
        return true;
    }

}
