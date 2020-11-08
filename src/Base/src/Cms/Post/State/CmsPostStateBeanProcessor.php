<?php

namespace Pars\Base\Cms\Post\State;

use Pars\Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Processor\AbstractBeanProcessor;

/**
 * Class CmsPostStateBeanProcessor
 * @package Pars\Base\Cms\Post\State
 */
class CmsPostStateBeanProcessor extends AbstractBeanProcessor
{


    /**
     * CmsPostStateBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsPostState_Code', 'CmsPostState_Code', 'CmsPostState', 'CmsPostState_Code', true);
        $saver->addColumn('CmsPostState_Active', 'CmsPostState_Active', 'CmsPostState', 'CmsPostState_Code');
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
