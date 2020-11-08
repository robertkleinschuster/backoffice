<?php

namespace Pars\Base\Localization\Locale;

use Pars\Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Processor\AbstractBeanProcessor;
use Niceshops\Bean\Type\Base\BeanInterface;

/**
 * Class LocaleBeanProcessor
 * @package Pars\Base\Localization\Locale
 */
class LocaleBeanProcessor extends AbstractBeanProcessor
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('Locale_Code', 'Locale_Code', 'Locale', 'Locale_Code', true);
        $saver->addColumn('Locale_Name', 'Locale_Name', 'Locale', 'Locale_Code');
        $saver->addColumn('Locale_UrlCode', 'Locale_UrlCode', 'Locale', 'Locale_Code');
        $saver->addColumn('Locale_Active', 'Locale_Active', 'Locale', 'Locale_Code');
        $saver->addColumn('Locale_Order', 'Locale_Order', 'Locale', 'Locale_Code');
        parent::__construct($saver);
    }

    protected function beforeSave(BeanInterface $bean)
    {
        if (!$bean->hasData('Locale_Order') || $bean->getData('Locale_Order') === 0) {
            $order = 1;
            $finder = new LocaleBeanFinder($this->adapter);
            $finder->getBeanLoader()->addOrder('Locale_Order', true);
            $finder->limit(1, 0);
            if ($finder->count() == 1) {
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
