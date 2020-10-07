<?php


namespace Backoffice\Authorization\Permission;


use Backoffice\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Mvc\Helper\ValidationHelperAwareInterface;
use Mvc\Helper\ValidationHelperAwareTrait;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanProcessor;

class PermissionBeanProcessor extends AbstractBeanProcessor implements ValidationHelperAwareInterface
{
    use ValidationHelperAwareTrait;

    /**
     * UserBeanProcessor constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $saver = new DatabaseBeanSaver($adapter, 'UserPermission');
        $saver->setFieldColumnMap([
            'UserPermission_Code' => 'UserPermission_Code',
            'UserPermission_Active' => 'UserPermission_Active',
        ]);
        $saver->setPrimaryKeyList([
            'UserPermission_Code',
        ]);
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
