<?php
namespace Backoffice\Database\Updater;


use Backoffice\Authentication\Bean\UserBean;

class DataUpdater extends AbstractUpdater
{

    public function updateDataUserState()
    {
        $data_Map = [];
        $data_Map[] = [
            'UserState_Code' => UserBean::STATE_ACTIVE,
            'UserState_Active' => true,
        ];
        $data_Map[] = [
            'UserState_Code' => UserBean::STATE_INACTIVE,
            'UserState_Active' => true,
        ];
        $data_Map[] = [
            'UserState_Code' => UserBean::STATE_LOCKED,
            'UserState_Active' => true,
        ];
        return $this->saveDataMap('UserState', 'UserState_Code', $data_Map);
    }


    public function updateDataUserPermission()
    {
        $data_Map = [];
        $data_Map[] = [
            'UserPermission_Code' => 'default',
            'UserPermission_Active' => true,
        ];
        return $this->saveDataMap('UserPermission', 'UserPermission_Code', $data_Map);
    }

    public function updateDataUserRole()
    {
        $data_Map = [];
        $data_Map[] = [
            'UserRole_Code' => 'default',
            'UserRole_Active' => true,
        ];
        return $this->saveDataMap('UserRole', 'UserRole_Code', $data_Map);
    }
}
