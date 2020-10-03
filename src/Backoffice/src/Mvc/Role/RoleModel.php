<?php


namespace Backoffice\Mvc\Role;


use Backoffice\Authorization\Role\RoleBeanFinder;
use Backoffice\Authorization\Role\RoleBeanProcessor;
use Backoffice\Mvc\Base\BaseModel;

/**
 * Class UserRoleModel
 * @package Backoffice\Mvc\Model
 * @method RoleBeanFinder getFinder() : BeanFinderInterface
 * @method RoleBeanProcessor getProcessor() : BeanProcessorInterface
 */
class RoleModel extends BaseModel
{

    public function init()
    {
        $this->setFinder(new RoleBeanFinder($this->adapter));
        $this->setProcessor(new RoleBeanProcessor($this->adapter));
    }

}
