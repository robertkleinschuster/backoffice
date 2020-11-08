<?php

namespace Pars\Base\Cms\Menu\Type;

use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Finder\AbstractBeanFinder;

/**
 * Class CmsMenuTypeBeanFinder
 * @package Pars\Base\Cms\Menu\Type
 */
class CmsMenuTypeBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsMenuType_Code', 'CmsMenuType_Code', 'CmsMenuType', 'CmsMenuType_Code', true);
        $loader->addColumn('CmsMenuType_Active', 'CmsMenuType_Active', 'CmsMenuType', 'CmsMenuType_Code');
        parent::__construct($loader, new CmsMenuTypeBeanFactory());
    }

    public function setCmsMenuType_Active(bool $active): self
    {
        $this->getBeanLoader()->filterValue('CmsMenuType_Active', $active);
        return $this;
    }
}
