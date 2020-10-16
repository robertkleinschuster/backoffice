<?php


namespace Base\Authorization\RolePermission;


use Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Mvc\Helper\ValidationHelperAwareInterface;
use Mvc\Helper\ValidationHelperAwareTrait;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\BeanSaverInterface;

class RolePermissionBeanProcessor extends \NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor implements ValidationHelperAwareInterface
{
    use ValidationHelperAwareTrait;

    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter);
        $saver->addColumn('UserPermission_Code', 'UserPermission_Code', 'UserRole_UserPermission', 'UserPermission_Code', true);
        $saver->addColumn('UserRole_ID', 'UserRole_ID', 'UserRole_UserPermission', 'UserRole_ID', true);
        parent::__construct($saver);
    }


    /**
     * @inheritDoc
     */
    protected function validateForSave(BeanInterface $bean): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function validateForDelete(BeanInterface $bean): bool
    {
        return true;
    }
}
