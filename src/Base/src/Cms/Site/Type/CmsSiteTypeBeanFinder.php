<?php

namespace Pars\Base\Cms\Site\Type;

use Niceshops\Bean\Finder\AbstractBeanFinder;
use Pars\Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;

class CmsSiteTypeBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsSiteType_Code', 'CmsSiteType_Code', 'CmsSiteType', 'CmsSiteType_Code', true);
        $loader->addColumn('CmsSiteType_Active', 'CmsSiteType_Active', 'CmsSiteType', 'CmsSiteType_Code');
        parent::__construct($loader, new CmsSiteTypeBeanFactory());
    }

    public function setCmsSiteType_Active(bool $active): self
    {
        $this->getBeanLoader()->filterValue('CmsSiteType_Active', $active);
        return $this;
    }
}
