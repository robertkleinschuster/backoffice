<?php
namespace Backoffice\Authorization\Permission;


use Mezzio\Mvc\View\ComponentDataBeanInterface;
use NiceshopsDev\Bean\Database\AbstractDatabaseBean;

class PermissionBean extends AbstractDatabaseBean implements ComponentDataBeanInterface
{
    public function __construct()
    {
        $this->setDatabaseField('PermissionRole_Code', self::DATA_TYPE_STRING, [self::COLUMN_TYPE_PRIMARY_KEY]);
        $this->setDatabaseField('PermissionRole_Active', self::DATA_TYPE_BOOL);
    }


}
