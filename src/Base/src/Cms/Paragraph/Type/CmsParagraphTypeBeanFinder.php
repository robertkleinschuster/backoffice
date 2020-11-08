<?php

namespace Pars\Base\Cms\Paragraph\Type;

use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Finder\AbstractBeanFinder;
use Pars\Base\Database\DatabaseBeanLoader;

class CmsParagraphTypeBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsParagraphType_Code', 'CmsParagraphType_Code', 'CmsParagraphType', 'CmsParagraphType_Code', true);
        $loader->addColumn('CmsParagraphType_Active', 'CmsParagraphType_Active', 'CmsParagraphType', 'CmsParagraphType_Code');
        parent::__construct($loader, new CmsParagraphTypeBeanFactory());
    }

    public function setCmsParagraphType_Active(bool $active): self
    {
        $this->getBeanLoader()->filterValue('CmsParagraphType_Active', $active);
        return $this;
    }
}
