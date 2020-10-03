<?php


namespace Backoffice\Authorization\UserRole;


use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class UserRoleBean extends AbstractJsonSerializableBean
{
    public function __construct()
    {
        $this->setDataType('Person_ID', self::DATA_TYPE_INT);
        $this->setDataType('UserRole_ID', self::DATA_TYPE_INT);
    }

}
