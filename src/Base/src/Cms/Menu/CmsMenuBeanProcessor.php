<?php

namespace Pars\Base\Cms\Menu;

use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Niceshops\Bean\Finder\BeanFinderInterface;
use Niceshops\Bean\Processor\AbstractBeanProcessor;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Base\Database\DatabaseBeanSaver;
use Pars\Mvc\Helper\ValidationHelperAwareInterface;
use Pars\Mvc\Helper\ValidationHelperAwareTrait;

/**
 * Class CmsMenuBeanProcessor
 * @package Pars\Base\Cms\Menu
 */
class CmsMenuBeanProcessor extends AbstractBeanProcessor implements
    ValidationHelperAwareInterface,
    TranslatorAwareInterface
{
    use ValidationHelperAwareTrait;
    use TranslatorAwareTrait;

    private $adapter;
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsMenu_ID', 'CmsMenu_ID', 'CmsMenu', 'CmsMenu_ID', true);
        $saver->addColumn('CmsMenu_ID_Parent', 'CmsMenu_ID_Parent', 'CmsMenu', 'CmsMenu_ID');
        $saver->addColumn('CmsPage_ID', 'CmsPage_ID', 'CmsMenu', 'CmsMenu_ID');
        $saver->addColumn('CmsPage_ID_Parent', 'CmsPage_ID_Parent', 'CmsMenu', 'CmsMenu_ID');
        $saver->addColumn('CmsMenu_Order', 'CmsMenu_Order', 'CmsMenu', 'CmsMenu_ID');
        $saver->addColumn('CmsMenuType_Code', 'CmsMenuType_Code', 'CmsMenu', 'CmsMenu_ID');
        $saver->addColumn('CmsMenuState_Code', 'CmsMenuState_Code', 'CmsMenu', 'CmsMenu_ID');
        parent::__construct($saver);
    }

    protected function beforeSave(BeanInterface $bean)
    {
        if (!$bean->hasData('CmsMenu_Order') || $bean->getData('CmsMenu_Order') === 0) {
            $order = 1;
            $finder = new CmsMenuBeanFinder($this->adapter);
            $finder->order(['CmsMenu_Order' => BeanFinderInterface::ORDER_MODE_ASC]);
            $finder->limit(1, 0);
            if ($finder->count() == 1) {
                $lastBean = $finder->getBean();
                if ($lastBean->hasData('CmsMenu_Order')) {
                    $order = $lastBean->getData('CmsMenu_Order') + 1;
                }
            }
            $bean->setData('CmsMenu_Order', $order);
        }
        parent::beforeSave($bean);
    }


    protected function translate(string $name): string
    {
        return $this->getTranslator()->translate($name, 'validation');
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        if (!$bean->hasData('CmsMenuState_Code') || !strlen(trim(($bean->getData('CmsMenuState_Code'))))) {
            $this->getValidationHelper()->addError('CmsMenuState_Code', $this->translate('articlestate.code.empty'));
        }
        if (!$bean->hasData('CmsMenuType_Code') || !strlen(trim(($bean->getData('CmsMenuType_Code'))))) {
            $this->getValidationHelper()->addError('CmsMenuType_Code', $this->translate('articletype.code.empty'));
        }
        return parent::validateForSave($bean);
    }
}
