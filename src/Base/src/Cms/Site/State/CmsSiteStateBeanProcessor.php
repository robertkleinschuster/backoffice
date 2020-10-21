<?php


namespace Base\Cms\Site\State;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsSiteStateBeanProcessor extends AbstractBeanProcessor
{


    /**
     * CmsSiteStateBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsSiteState_Code', 'CmsSiteState_Code', 'CmsSiteState', 'CmsSiteState_Code', true);
        $saver->addColumn('CmsSiteState_Active', 'CmsSiteState_Active', 'CmsSiteState', 'CmsSiteState_Code');
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
