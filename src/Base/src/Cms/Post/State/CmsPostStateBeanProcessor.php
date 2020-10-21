<?php


namespace Base\Cms\Post\State;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class CmsPostStateBeanProcessor extends AbstractBeanProcessor
{


    /**
     * CmsPostStateBeanProcessor constructor.
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('CmsPostState_Code', 'CmsPostState_Code', 'CmsPostState', 'CmsPostState_Code', true);
        $saver->addColumn('CmsPostState_Active', 'CmsPostState_Active', 'CmsPostState', 'CmsPostState_Code');
        parent::__construct($saver);
    }

    protected function validateForSave(BeanInterface $bean): bool
    {
        return true;
    }

    protected function validateForDelete(BeanInterface $bean): bool
    {
       return true;
    }

}
