<?php

namespace Backoffice\Authorization\Role;

use Mezzio\Mvc\View\ComponentDataBeanInterface;
use NiceshopsDev\Bean\Database\AbstractDatabaseBean;

class RoleBean extends AbstractDatabaseBean implements ComponentDataBeanInterface
{
    public function __construct()
    {
        $this->setDatabaseField('UserRole_Code', self::DATA_TYPE_STRING, [self::COLUMN_TYPE_PRIMARY_KEY]);
        $this->setDatabaseField('UserRole_Active', self::DATA_TYPE_BOOL);
        $this->setDatabaseField('Permissions', self::DATA_TYPE_ARRAY);
    }

}
