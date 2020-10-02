<?php
namespace Backoffice\Authorization\Role;

use Backoffice\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;
use NiceshopsDev\Bean\BeanProcessor\BeanSaverInterface;

class RoleBeanProcessor extends AbstractBeanProcessor
{
    public function __construct(Adapter $adapter)
    {
        parent::__construct(new DatabaseBeanSaver($adapter, 'Role'));
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
