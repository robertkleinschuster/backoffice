<?php


namespace Base\Cms\Post\Type;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;

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
        $this->getLoader()->filterValue('CmsPostType_Active', $active);
        return $this;
    }
}
