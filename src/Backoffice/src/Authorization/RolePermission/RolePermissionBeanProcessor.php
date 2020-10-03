<?php


namespace Backoffice\Authorization\RolePermission;


use Backoffice\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Mezzio\Mvc\Helper\ValidationHelperAwareInterface;
use Mezzio\Mvc\Helper\ValidationHelperAwareTrait;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\BeanSaverInterface;

class RolePermissionBeanProcessor extends \NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor implements ValidationHelperAwareInterface
{
    use ValidationHelperAwareTrait;

    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter, 'UserRole_UserPermission');
        $saver->setFieldColumnMap([
            'UserRole_ID' => 'UserRole_ID',
            'UserPermission_Code' => 'UserPermission_Code',
        ]);
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
