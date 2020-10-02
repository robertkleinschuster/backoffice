<?php


namespace Backoffice\Authorization\Permission;


use Backoffice\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class PermissionBeanProcessor extends AbstractBeanProcessor
{


    /**
     * UserBeanProcessor constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct(new DatabaseBeanSaver($adapter, 'Permission'));
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
