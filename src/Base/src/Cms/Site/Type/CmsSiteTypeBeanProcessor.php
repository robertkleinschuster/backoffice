<?php


namespace Base\Cms\Site\Type;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsSiteTypeBeanProcessor extends AbstractBeanProcessor
{

    /**
     * CmsSiteStateBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsSiteType_Code', 'CmsSiteType_Code', 'CmsSiteType', 'CmsSiteType_Code', true);
        $saver->addColumn('CmsSiteType_Active', 'CmsSiteType_Active', 'CmsSiteType', 'CmsSiteType_Code');
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
