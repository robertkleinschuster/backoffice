<?php


namespace Base\Cms\Post\State;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;
use NiceshopsDev\Bean\BeanFinder\BeanLoaderInterface;

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
        $this->getLoader()->filterValue('CmsPostState_Active', $active);
        return $this;
    }

}
