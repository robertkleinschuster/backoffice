<?php
namespace Backoffice\Authorization\Permission;


use Mezzio\Mvc\View\ComponentDataBeanInterface;
use NiceshopsDev\Bean\Database\AbstractDatabaseBean;

class PermissionBean extends AbstractDatabaseBean implements ComponentDataBeanInterface
{
    public function __construct()
    {
        $this->setDataType('UserPermission_Code', self::DATA_TYPE_STRING, true);
        $this->setDataType('UserPermission_Active', self::DATA_TYPE_BOOL, true);
    }


}
