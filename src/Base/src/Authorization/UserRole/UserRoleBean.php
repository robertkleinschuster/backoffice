<?php

namespace Pars\Base\Authorization\UserRole;

use Pars\Base\Authorization\Permission\PermissionBeanList;
use Niceshops\Bean\Type\JsonSerializable\AbstractJsonSerializableBean;

/**
 * Class UserRoleBean
 * @package Pars\Base\Authorization\UserRole
 */
class UserRoleBean extends AbstractJsonSerializableBean
{
    public function __construct()
    {
        $this->setDataType('Person_ID', self::DATA_TYPE_INT);
        $this->setDataType('UserRole_ID', self::DATA_TYPE_INT);
        $this->setDataType('UserRole_Code', self::DATA_TYPE_STRING);
        $this->setDataType('UserRole_Active', self::DATA_TYPE_BOOL);
        $this->setDataType('UserPermission_BeanList', self::DATA_TYPE_ITERABLE);
        $this->setData('UserPermission_BeanList', new PermissionBeanList());
    }

    /**
     * @param array $arrData
     * @return UserRoleBean|mixed
     */
    public static function createFromArray(array $arrData)
    {
        $bean = parent::createFromArray($arrData);
        $permissionBeanList = new PermissionBeanList();
        $permissionBeanList->setSerializeData($bean->getData('UserPermission_BeanList'));
        $bean->setData('UserPermission_BeanList', $permissionBeanList);
        return $bean;
    }
}
