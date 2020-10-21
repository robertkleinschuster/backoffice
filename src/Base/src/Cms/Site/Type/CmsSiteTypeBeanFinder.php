<?php


namespace Base\Cms\Site\Type;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

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
        $this->getLoader()->filterValue('CmsSiteType_Active', $active);
        return $this;
    }
}
