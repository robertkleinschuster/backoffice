<?php

namespace Pars\Base\Cms\Post\Type;

use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Finder\AbstractBeanFinder;
use Pars\Base\Database\DatabaseBeanLoader;

/**
 * Class CmsPostTypeBeanFinder
 * @package Pars\Base\Cms\Post\Type
 */
class CmsPostTypeBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsPostType_Code', 'CmsPostType_Code', 'CmsPostType', 'CmsPostType_Code', true);
        $loader->addColumn('CmsPostType_Active', 'CmsPostType_Active', 'CmsPostType', 'CmsPostType_Code');
        parent::__construct($loader, new CmsPostTypeBeanFactory());
    }

    public function setCmsPostType_Active(bool $active): self
    {
        $this->getBeanLoader()->filterValue('CmsPostType_Active', $active);
        return $this;
    }
}
