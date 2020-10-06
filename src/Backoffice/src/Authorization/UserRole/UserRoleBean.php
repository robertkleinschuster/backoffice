<?php


namespace Backoffice\Authorization\UserRole;


use Backoffice\Authorization\Permission\PermissionBeanList;
use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class UserRoleBean extends AbstractJsonSerializableBean
{
    public function __construct()
    {
        $this->setDataType('Person_ID', self::DATA_TYPE_INT);
        $this->setDataType('UserRole_ID', self::DATA_TYPE_INT);
        $this->setDataType('UserRole_Code', self::DATA_TYPE_STRING);
        $this->setDataType('UserPermission_BeanList', self::DATA_TYPE_ITERABLE);
        $this->setData('UserPermission_BeanList', new PermissionBeanList());

    }

    /**
     * @param array $arrData
     * @return UserRoleBean|mixed
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
