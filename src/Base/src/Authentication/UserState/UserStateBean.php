<?php

namespace Pars\Base\Authentication\UserState;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

class UserStateBean extends AbstractBaseBean
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
