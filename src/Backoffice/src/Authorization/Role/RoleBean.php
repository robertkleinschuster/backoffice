<?php

namespace Backoffice\Authorization\Role;

use Mezzio\Mvc\View\ComponentDataBeanInterface;
use NiceshopsDev\Bean\Database\AbstractDatabaseBean;

class RoleBean extends AbstractDatabaseBean implements ComponentDataBeanInterface
{
    public function __construct()
    {
        $this->setDataType('UserRole_ID', self::DATA_TYPE_INT, true);
        $this->setDataType('UserRole_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('UserRole_Active', self::DATA_TYPE_BOOL, true);
        $this->setDataType('Permissions', self::DATA_TYPE_ARRAY, true);
    }

}
