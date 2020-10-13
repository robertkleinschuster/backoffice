<?php
namespace Backoffice\Authorization\Role;

use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Mvc\Helper\ValidationHelperAwareInterface;
use Mvc\Helper\ValidationHelperAwareTrait;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class RoleBeanProcessor extends AbstractBeanProcessor implements ValidationHelperAwareInterface
{
    use ValidationHelperAwareTrait;

    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('UserRole_ID', 'UserRole_ID', 'UserRole', 'UserRole_ID', true);
        $saver->addColumn('UserRole_Code', 'UserRole_Code', 'UserRole', 'UserRole_ID');
        $saver->addColumn('UserRole_Active', 'UserRole_Active', 'UserRole', 'UserRole_ID');

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
