<?php


namespace Base\Authentication\UserState;


use Base\Authorization\Role\RoleBeanList;
use Mezzio\Authentication\UserInterface;
use NiceshopsDev\Bean\JsonSerializable\AbstractJsonSerializableBean;

class UserStateBean extends AbstractJsonSerializableBean
{

    /**
     * UserBean constructor.
     */
    public function __construct()
    {
        $this->setDataType('UserState_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('UserState_Active', self::DATA_TYPE_BOOL, true);

    }

}
