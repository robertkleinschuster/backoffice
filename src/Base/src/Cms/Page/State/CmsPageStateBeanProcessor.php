<?php

namespace Pars\Base\Cms\Page\State;

use Pars\Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Processor\AbstractBeanProcessor;

/**
 * Class CmsPageStateBeanProcessor
 * @package Pars\Base\Cms\Page\State
 */
class CmsPageStateBeanProcessor extends AbstractBeanProcessor
{


    /**
     * CmsPageStateBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsPageState_Code', 'CmsPageState_Code', 'CmsPageState', 'CmsPageState_Code', true);
        $saver->addColumn('CmsPageState_Active', 'CmsPageState_Active', 'CmsPageState', 'CmsPageState_Code');
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
