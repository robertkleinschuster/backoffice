<?php


namespace Base\Cms\Menu\State;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsMenuStateBeanProcessor extends AbstractBeanProcessor
{


    /**
     * CmsMenuStateBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsMenuState_Code', 'CmsMenuState_Code', 'CmsMenuState', 'CmsMenuState_Code', true);
        $saver->addColumn('CmsMenuState_Active', 'CmsMenuState_Active', 'CmsMenuState', 'CmsMenuState_Code');
        parent::__construct($saver);
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        return true;
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
       return true;
    }

}