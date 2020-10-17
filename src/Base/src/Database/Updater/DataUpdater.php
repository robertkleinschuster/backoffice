<?php
namespace Base\Database\Updater;


use Base\Article\ArticleBean;
use Base\Authentication\User\UserBean;

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

    public function updateDataLocale()
    {
        $data_Map = [];
        $data_Map[] = [
            'Locale_Code' => 'de_AT',
            'Locale_Name' => 'Deutsch (Österreich)',
            'Locale_Active' => true,
        ];
        $data_Map[] = [
            'Locale_Code' => 'de_DE',
            'Locale_Name' => 'Deutsch (Deutschland)',
            'Locale_Active' => true,
        ];
        $data_Map[] = [
            'Locale_Code' => 'en_US',
            'Locale_Name' => 'English (United States)',
            'Locale_Active' => false,
        ];
        return $this->saveDataMap('Locale', 'Locale_Code', $data_Map);
    }

    public function updateDataArticleState()
    {
        $data_Map = [];
        $data_Map[] = [
            'ArticleState_Code' => ArticleBean::STATE_ACTIVE,
            'ArticleState_Active' => true,
        ];
        $data_Map[] = [
            'ArticleState_Code' => ArticleBean::STATE_INACTIVE,
            'ArticleState_Active' => true,
        ];
        return $this->saveDataMap('ArticleState', 'ArticleState_Code', $data_Map);
    }


    public function updateDataArticleType()
    {
        $data_Map = [];
        $data_Map[] = [
            'ArticleType_Code' => ArticleBean::TYPE_DEFAULT,
            'ArticleType_Active' => true,
        ];
        return $this->saveDataMap('ArticleType', 'ArticleType_Code', $data_Map);
    }


    public function updateDataUserPermission()
    {
        $data_Map = [];



        $data_Map[] = [
            'UserPermission_Code' => 'article',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'article.delete',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'article.create',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'article.edit',
            'UserPermission_Active' => true,
        ];


        $data_Map[] = [
            'UserPermission_Code' => 'cmsmenu',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmsmenu.delete',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmsmenu.create',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmsmenu.edit',
            'UserPermission_Active' => true,
        ];


        $data_Map[] = [
            'UserPermission_Code' => 'cmssite',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmssite.delete',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmssite.create',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmssite.edit',
            'UserPermission_Active' => true,
        ];


        $data_Map[] = [
            'UserPermission_Code' => 'cmsparagraph',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmsparagraph.delete',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmsparagraph.create',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmsparagraph.edit',
            'UserPermission_Active' => true,
        ];

        $data_Map[] = [
            'UserPermission_Code' => 'cmssiteparagraph',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmssiteparagraph.delete',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmssiteparagraph.create',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'cmssiteparagraph.edit',
            'UserPermission_Active' => true,
        ];


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
            'UserPermission_Code' => 'user.edit.state',
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
            'UserPermission_Code' => 'translation',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'translation.delete',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'translation.create',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'translation.edit',
            'UserPermission_Active' => true,
        ];

        $data_Map[] = [
            'UserPermission_Code' => 'locale',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'locale.delete',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'locale.create',
            'UserPermission_Active' => true,
        ];
        $data_Map[] = [
            'UserPermission_Code' => 'locale.edit',
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
