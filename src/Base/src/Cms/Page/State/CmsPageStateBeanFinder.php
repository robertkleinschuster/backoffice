<?php

namespace Pars\Base\Cms\Page\State;

use Niceshops\Bean\Finder\AbstractBeanFinder;
use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;

/**
 * Class CmsPageStateBeanFinder
 * @package Pars\Base\Cms\Page\State
 */
class CmsPageStateBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsPageState_Code', 'CmsPageState_Code', 'CmsPageState', 'CmsPageState_Code', true);
        $loader->addColumn('CmsPageState_Active', 'CmsPageState_Active', 'CmsPageState', 'CmsPageState_Code');
        parent::__construct($loader, new CmsPageStateBeanFactory());
    }

    public function setCmsPageState_Active(bool $active): self
    {
        $this->getBeanLoader()->filterValue('CmsPageState_Active', $active);
        return $this;
    }
}
