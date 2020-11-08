<?php

namespace Pars\Base\Cms\Site\Type;

use Pars\Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Processor\AbstractBeanProcessor;

/**
 * Class CmsSiteTypeBeanProcessor
 * @package Pars\Base\Cms\Site\Type
 */
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
