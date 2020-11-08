<?php

namespace Pars\Base\Cms\Post\State;

use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Finder\AbstractBeanFinder;
use Pars\Base\Database\DatabaseBeanLoader;

/**
 * Class CmsPostStateBeanFinder
 * @package Pars\Base\Cms\Post\State
 */
class CmsPostStateBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsPostState_Code', 'CmsPostState_Code', 'CmsPostState', 'CmsPostState_Code', true);
        $loader->addColumn('CmsPostState_Active', 'CmsPostState_Active', 'CmsPostState', 'CmsPostState_Code');
        parent::__construct($loader, new CmsPostStateBeanFactory());
    }

    public function setCmsPostState_Active(bool $active): self
    {
        $this->getBeanLoader()->filterValue('CmsPostState_Active', $active);
        return $this;
    }
}
