<?php

namespace Pars\Base\Cms\Site\State;

use Niceshops\Bean\Finder\AbstractBeanFinder;
use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;

/**
 * Class CmsSiteStateBeanFinder
 * @package Pars\Base\Cms\Site\State
 */
class CmsSiteStateBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsSiteState_Code', 'CmsSiteState_Code', 'CmsSiteState', 'CmsSiteState_Code', true);
        $loader->addColumn('CmsSiteState_Active', 'CmsSiteState_Active', 'CmsSiteState', 'CmsSiteState_Code');
        parent::__construct($loader, new CmsSiteStateBeanFactory());
    }

    public function setCmsSiteState_Active(bool $active): self
    {
        $this->getBeanLoader()->filterValue('CmsSiteState_Active', $active);
        return $this;
    }
}
