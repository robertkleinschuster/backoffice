<?php

namespace Pars\Base\Authorization\RolePermission;

use Pars\Base\Database\DatabaseBeanSaver;
use Laminas\Db\Adapter\Adapter;
use Niceshops\Bean\Processor\AbstractBeanProcessor;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Mvc\Helper\ValidationHelperAwareInterface;
use Pars\Mvc\Helper\ValidationHelperAwareTrait;

/**
 * Class RolePermissionBeanProcessor
 * @package Pars\Base\Authorization\RolePermission
 */
class RolePermissionBeanProcessor extends AbstractBeanProcessor implements ValidationHelperAwareInterface
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
