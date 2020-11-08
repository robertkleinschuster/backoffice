<?php

namespace Pars\Base\Cms\Menu\State;

use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Finder\AbstractBeanFinder;

/**
 * Class CmsMenuStateBeanFinder
 * @package Pars\Base\Cms\Menu\State
 */
class CmsMenuStateBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsMenuState_Code', 'CmsMenuState_Code', 'CmsMenuState', 'CmsMenuState_Code', true);
        $loader->addColumn('CmsMenuState_Active', 'CmsMenuState_Active', 'CmsMenuState', 'CmsMenuState_Code');
        parent::__construct($loader, new CmsMenuStateBeanFactory());
    }

    public function setCmsMenuState_Active(bool $active): self
    {
        $this->getBeanLoader()->filterValue('CmsMenuState_Active', $active);
        return $this;
    }
}
