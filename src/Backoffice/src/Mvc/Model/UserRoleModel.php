<?php


namespace Backoffice\Mvc\Model;


use Backoffice\Authorization\Role\RoleBeanFinder;
use Backoffice\Authorization\Role\RoleBeanProcessor;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;

class UserRoleModel extends BaseModel
{

    public function init()
    {
        $this->setFinder(new RoleBeanFinder($this->adapter));
        $this->setProcessor(new RoleBeanProcessor($this->adapter));
    }

    public function submit(array $attributes)
    {
        parent::submit($attributes);
        if ($attributes['submit'] == 'linktouser') {
            $sql = new Sql($this->adapter);
            $insert = $sql->insert('User_UserRole');
            $insert->columns(['Person_ID', 'UserRole_ID']);
            $insert->values([$attributes['Person_ID'], $attributes['UserRole_ID']]);
            $result = $this->adapter->query($sql->buildSqlString($insert), Adapter::QUERY_MODE_EXECUTE);
        }
        if ($attributes['submit'] == 'deletefromuser') {
            $sql = new Sql($this->adapter);
            $insert = $sql->delete('User_UserRole');
            $insert->where([['Person_ID' => $attributes['Person_ID']], ['UserRole_ID' => $attributes['UserRole_ID']]]);
            $result = $this->adapter->query($sql->buildSqlString($insert), Adapter::QUERY_MODE_EXECUTE);
        }
        if ($attributes['submit'] == 'linktopermission') {
            $sql = new Sql($this->adapter);
            $insert = $sql->insert('UserRole_UserPermission');
            $insert->columns(['UserPermission_Code', 'UserRole_ID']);
            $insert->values([$attributes['UserPermission_Code'], $attributes['UserRole_ID']]);
            $result = $this->adapter->query($sql->buildSqlString($insert), Adapter::QUERY_MODE_EXECUTE);
        }
        if ($attributes['submit'] == 'deletefromrole') {
            $sql = new Sql($this->adapter);
            $insert = $sql->delete('UserRole_UserPermission');
            $insert->where([['UserPermission_Code' => $attributes['UserPermission_Code']], ['UserRole_ID' => $attributes['UserRole_ID']]]);
            $result = $this->adapter->query($sql->buildSqlString($insert), Adapter::QUERY_MODE_EXECUTE);
        }
    }


}
