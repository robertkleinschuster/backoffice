<?php
namespace Base\Database\Updater;


use Base\Authentication\Bean\UserBean;

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
            'UserPermission_Code' => 'user',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'user.delete',
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
            'UserPermission_Code' => 'role',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'role.delete',
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

        $data_Map[] = [
            'UserPermission_Code' => 'userrole',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'userrole.delete',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'userrole.create',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'userrole.edit',
            'UserPermission_Active' => true,
        ];

        $data_Map[] = [
            'UserPermission_Code' => 'rolepermission',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'rolepermission.delete',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'rolepermission.create',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'rolepermission.edit',
            'UserPermission_Active' => true,
        ];

        $data_Map[] = [
            'UserPermission_Code' => 'update',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'update.schema',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'update.data',
            'UserPermission_Active' => true,
        ];

        $data_Map[] = [
            'UserPermission_Code' => 'debug',
            'UserPermission_Active' => true,
        ];

        return $this->saveDataMap('UserPermission', 'UserPermission_Code', $data_Map);
    }

}
