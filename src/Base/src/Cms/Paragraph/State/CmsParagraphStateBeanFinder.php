<?php


namespace Base\Cms\Paragraph\State;


use Base\Database\DatabaseBeanLoader;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanFactory\BeanFactoryInterface;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanFinder;
use NiceshopsDev\Bean\BeanFinder\BeanLoaderInterface;

class CmsParagraphStateBeanFinder extends AbstractBeanFinder
{
    public function __construct(Adapter $adapter)
    {
        $loader = new DatabaseBeanLoader($adapter);
        $loader->addColumn('CmsParagraphState_Code', 'CmsParagraphState_Code', 'CmsParagraphState', 'CmsParagraphState_Code', true);
        $loader->addColumn('CmsParagraphState_Active', 'CmsParagraphState_Active', 'CmsParagraphState', 'CmsParagraphState_Code');
        parent::__construct($loader, new CmsParagraphStateBeanFactory());
    }

    public function setCmsParagraphState_Active(bool $active): self
    {
        $this->getLoader()->filterValue('CmsParagraphState_Active', $active);
        return $this;
    }

}
