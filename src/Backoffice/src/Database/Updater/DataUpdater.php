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
            'UserPermission_Code' => 'user.view',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'user.create',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'user.edit',
            'UserPermission_Active' => true,
        ];

        $data_Map[] = [
            'UserPermission_Code' => 'role.view',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'role.create',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'role.edit',
            'UserPermission_Active' => true,
        ];

        return $this->saveDataMap('UserPermission', 'UserPermission_Code', $data_Map);
    }

}
