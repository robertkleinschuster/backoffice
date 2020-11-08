<?php

namespace Pars\Base\Cms\Site\State;

use Pars\Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Processor\AbstractBeanProcessor;

/**
 * Class CmsSiteStateBeanProcessor
 * @package Pars\Base\Cms\Site\State
 */
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
