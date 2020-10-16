<?php

namespace Base\Authorization\Role;

use Base\Authorization\Permission\PermissionBeanList;
use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class RoleBean extends AbstractJsonSerializableBean
{
    public function __construct()
    {
        $this->setDataType('UserRole_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('UserRole_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('UserRole_Active', self::DATA_TYPE_BOOL, true);
        $this->setDataType('UserPermission_BeanList', self::DATA_TYPE_ITERABLE, true);
        $this->setData('UserPermission_BeanList', new PermissionBeanList());
    }

    /**
     * @param array $arrData
     * @return RoleBean|mixed
     * @throws \NiceshopsDev\Bean\BeanException
     * @throws \NiceshopsDev\Bean\BeanList\BeanListException
     */
    static public function createFromArray(array $arrData)
    {
        $bean = parent::createFromArray($arrData);
        $permissionBeanList = new PermissionBeanList();
        $permissionBeanList->setSerializeData($bean->getData('UserPermission_BeanList'));
        $bean->setData('UserPermission_BeanList', $permissionBeanList);
        return $bean;
    }


}
