<?php


namespace Base\Cms\Site\State;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;
use NiceshopsDev\Bean\BeanFinder\BeanLoaderInterface;

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
        $this->getLoader()->filterValue('CmsSiteState_Active', $active);
        return $this;
    }

}
