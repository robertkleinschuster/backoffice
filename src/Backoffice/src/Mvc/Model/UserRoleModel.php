<?php


namespace Backoffice\Mvc\Model;


use Backoffice\Authorization\Role\RoleBeanFinder;
use Backoffice\Authorization\Role\RoleBeanProcessor;

class UserRoleModel extends BaseModel
{

    public function init()
    {
        $this->setFinder(new RoleBeanFinder($this->adapter));
        $this->setProcessor(new RoleBeanProcessor($this->adapter));
    }
}
