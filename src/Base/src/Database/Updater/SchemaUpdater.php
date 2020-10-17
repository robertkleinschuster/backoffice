<?php

namespace Base\Database\Updater;


use Laminas\Db\Sql\Ddl\Column\Boolean;
use Laminas\Db\Sql\Ddl\Column\Integer;
use Laminas\Db\Sql\Ddl\Column\Text;
use Laminas\Db\Sql\Ddl\Column\Varchar;
use Laminas\Db\Sql\Ddl\Constraint\ForeignKey;
use Laminas\Db\Sql\Ddl\Constraint\PrimaryKey;
use Laminas\Db\Sql\Ddl\Constraint\UniqueKey;
use Laminas\Db\Sql\Ddl\Index\Index;
use Laminas\Db\Sql\Predicate\In;

class SchemaUpdater extends AbstractUpdater
{

    public function updateTableLocale()
    {
        $table = $this->getTableStatement('Locale');
        $this->addColumnToTable($table, new Varchar('Locale_Code', 255));
        $this->addColumnToTable($table, new Varchar('Locale_Name', 255));
        $this->addColumnToTable($table, new Boolean('Locale_Active'));
        $this->addConstraintToTable($table, new PrimaryKey('Locale_Code'));
        $this->addDefaultColumnsToTable($table);
        $this->query($table);
    }

    public function updateTablePerson()
    {
        $table = $this->getTableStatement('Person');
        $personId = new Integer('Person_ID');
        $personId->setOption('AUTO_INCREMENT', true);
        $this->addColumnToTable($table, $personId);
        $this->addColumnToTable($table, new Varchar('Person_Firstname', 255));
        $this->addColumnToTable($table, new Varchar('Person_Lastname', 255));
        $this->addConstraintToTable($table, new PrimaryKey('Person_ID'));
        $this->addDefaultColumnsToTable($table);
        return $this->query($table);
    }

    public function updateTableUserState()
    {
        $table = $this->getTableStatement('UserState');
        $this->addColumnToTable($table, new Varchar('UserState_Code', 255));
        $this->addColumnToTable($table, new Boolean('UserState_Active', 255));
        $this->addConstraintToTable($table, new PrimaryKey('UserState_Code'));
        $this->addDefaultColumnsToTable($table);
        return $this->query($table);
    }

    public function updateTableUser()
    {
        $table = $this->getTableStatement('User');
        $this->addColumnToTable($table, new Integer('Person_ID'));
        $this->addColumnToTable($table, new Varchar('UserState_Code', 255));
        $this->addColumnToTable($table, new Varchar('User_Username', 255));
        $this->addColumnToTable($table, new Varchar('User_Displayname', 255));
        $this->addColumnToTable($table, new Varchar('User_Password', 255));
        $this->addColumnToTable($table, new Varchar('Locale_Code', 255, true));
        $this->addConstraintToTable($table, new PrimaryKey('Person_ID'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'Person_ID', 'Person', 'Person_ID', 'CASCADE'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'UserState_Code', 'UserState', 'UserState_Code'));
        $this->addConstraintToTable($table, new UniqueKey('User_Username'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'Locale_Code', 'Locale', 'Locale_Code'));
        $this->addDefaultColumnsToTable($table);
        return $this->query($table);
    }


    public function updateTableUserRole()
    {
        $table = $this->getTableStatement('UserRole');
        $this->addColumnToTable($table, new Integer('UserRole_ID'))
            ->setOption('AUTO_INCREMENT', true);
        $this->addColumnToTable($table, new Varchar('UserRole_Code', 255));
        $this->addColumnToTable($table, new Boolean('UserRole_Active'))
            ->setDefault(true);
        $this->addConstraintToTable($table, new PrimaryKey('UserRole_ID'));
        $this->addConstraintToTable($table, new Index('UserRole_Code'));
        $this->addConstraintToTable($table, new UniqueKey('UserRole_Code'));
        $this->addDefaultColumnsToTable($table);

        return $this->query($table);
    }

    public function updateTableUser_UserRole()
    {
        $table = $this->getTableStatement('User_UserRole');
        $this->addColumnToTable($table, new Integer('Person_ID'));
        $this->addColumnToTable($table, new Integer('UserRole_ID'));
        $this->addConstraintToTable($table, new PrimaryKey(['Person_ID', 'UserRole_ID']));
        $this->addConstraintToTable($table, new ForeignKey(null, 'Person_ID', 'User', 'Person_ID', 'CASCADE'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'UserRole_ID', 'UserRole', 'UserRole_ID', 'CASCADE'));
        $this->addDefaultColumnsToTable($table);

        return $this->query($table);

    }

    public function updateTableUserPermission()
    {
        $table = $this->getTableStatement('UserPermission');
        $this->addColumnToTable($table, new Varchar('UserPermission_Code', 255));
        $this->addColumnToTable($table, new Boolean('UserPermission_Active'));
        $this->addConstraintToTable($table, new PrimaryKey('UserPermission_Code'));
        $this->addDefaultColumnsToTable($table);

        return $this->query($table);
    }

    public function updateTableUserRole_UserPermission()
    {
        $table = $this->getTableStatement('UserRole_UserPermission');
        $this->addColumnToTable($table, new Integer('UserRole_ID'));
        $this->addColumnToTable($table, new Varchar('UserPermission_Code', 255));
        $this->addConstraintToTable($table, new PrimaryKey(['UserRole_ID', 'UserPermission_Code']));
        $this->addConstraintToTable($table, new ForeignKey(null, 'UserRole_ID', 'UserRole', 'UserRole_ID', 'CASCADE'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'UserPermission_Code', 'UserPermission', 'UserPermission_Code', 'CASCADE'));
        $this->addDefaultColumnsToTable($table);

        return $this->query($table);
    }



    public function updateTableTranslation()
    {
        $table = $this->getTableStatement('Translation');
        $this->addColumnToTable($table, new Integer('Translation_ID'))
            ->setOption('AUTO_INCREMENT', true);;
        $this->addColumnToTable($table, new Varchar('Translation_Code', 255));
        $this->addColumnToTable($table, new Varchar('Locale_Code', 255));
        $this->addColumnToTable($table, new Varchar('Translation_Namespace', 255));
        $this->addColumnToTable($table, new Text('Translation_Text', 65535));
        $this->addConstraintToTable($table, new PrimaryKey('Translation_ID'));
        $this->addConstraintToTable($table, new UniqueKey(['Translation_Code', 'Locale_Code', 'Translation_Namespace']));
        $this->addConstraintToTable($table, new ForeignKey(null, 'Locale_Code', 'Locale', 'Locale_Code'));
        $this->addDefaultColumnsToTable($table);

        return $this->query($table);
    }

    public function updateTableArticleState()
    {
        $table = $this->getTableStatement('ArticleState');
        $this->addColumnToTable($table, new Varchar('ArticleState_Code', 255));
        $this->addColumnToTable($table, new Boolean('ArticleState_Active'));
        $this->addConstraintToTable($table, new PrimaryKey('ArticleState_Code'));
        $this->addDefaultColumnsToTable($table);
        return $this->query($table);
    }

    public function updateTableArticleType()
    {
        $table = $this->getTableStatement('ArticleType');
        $this->addColumnToTable($table, new Varchar('ArticleType_Code', 255));
        $this->addColumnToTable($table, new Boolean('ArticleType_Active'));
        $this->addConstraintToTable($table, new PrimaryKey('ArticleType_Code'));
        $this->addDefaultColumnsToTable($table);
        return $this->query($table);

    }

    public function updateTableArticle()
    {
        $table = $this->getTableStatement('Article');
        $this->addColumnToTable($table, new Integer('Article_ID'))
            ->setOption('AUTO_INCREMENT', true);
        $this->addColumnToTable($table, new Varchar('Article_Code', 255, true));
        $this->addColumnToTable($table, new Boolean('ArticleState_Code', 255));
        $this->addColumnToTable($table, new Varchar('ArticleType_Code', 255));
        $this->addConstraintToTable($table, new PrimaryKey('Article_ID'));
        $this->addConstraintToTable($table, new UniqueKey('Article_Code'));
        $this->addDefaultColumnsToTable($table);
        return $this->query($table);
    }

    public function updateTableArticleTranslation()
    {
        $table = $this->getTableStatement('ArticleTranslation');
        $this->addColumnToTable($table, new Integer('Article_ID'));
        $this->addColumnToTable($table, new Varchar('Locale_Code', 255));
        $this->addColumnToTable($table, new Varchar('ArticleTranslation_Code', 255));
        $this->addColumnToTable($table, new Varchar('ArticleTranslation_Name', 255));
        $this->addColumnToTable($table, new Varchar('ArticleTranslation_Title', 255, true));
        $this->addColumnToTable($table, new Varchar('ArticleTranslation_Heading', 255, true));
        $this->addColumnToTable($table, new Varchar('ArticleTranslation_SubHeading', 255, true));
        $this->addColumnToTable($table, new Text('ArticleTranslation_Teaser', 65535, true));
        $this->addColumnToTable($table, new Text('ArticleTranslation_Text', 65535, true));
        $this->addConstraintToTable($table, new PrimaryKey(['Article_ID', 'Locale_Code']));
        $this->addConstraintToTable($table, new ForeignKey(null, 'Article_ID', 'Article', 'Article_ID'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'Locale_Code', 'Locale', 'Locale_Code'));
        $this->addConstraintToTable($table, new UniqueKey(['Locale_Code', 'ArticleTranslation_Code']));
        $this->addDefaultColumnsToTable($table);
        return $this->query($table);
    }

    public function updateTableCmsSite()
    {
        $table = $this->getTableStatement('CmsSite');
        $this->addColumnToTable($table, new Integer('CmsSite_ID'))
            ->setOption('AUTO_INCREMENT', true);
        $this->addColumnToTable($table, new Integer('Article_ID'));
        $this->addConstraintToTable($table, new PrimaryKey('CmsSite_ID'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'Article_ID', 'Article', 'Article_ID'));
        $this->addDefaultColumnsToTable($table);
        return $this->query($table);
    }


    public function updateTableCmsParagraph()
    {
        $table = $this->getTableStatement('CmsParagraph');
        $this->addColumnToTable($table, new Integer('CmsParagraph_ID'))
            ->setOption('AUTO_INCREMENT', true);
        $this->addColumnToTable($table, new Integer('CmsSite_ID'));
        $this->addColumnToTable($table, new Integer('Article_ID'));
        $this->addConstraintToTable($table, new PrimaryKey('CmsParagraph_ID'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'CmsSite_ID', 'CmsSite', 'CmsSite_ID'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'Article_ID', 'Article', 'Article_ID'));
        $this->addDefaultColumnsToTable($table);
        return $this->query($table);
    }

    public function updateTableCmsSite_CmsParagraph()
    {
        $table = $this->getTableStatement('CmsSite_CmsParagraph');
        $this->addColumnToTable($table, new Integer('CmsSite_ID'));
        $this->addColumnToTable($table, new Integer('CmsParagraph_ID'));
        $this->addConstraintToTable($table, new PrimaryKey(['CmsSite_ID', 'CmsParagraph_ID']));
        $this->addConstraintToTable($table, new ForeignKey(null, 'CmsSite_ID', 'CmsSite', 'CmsSite_ID'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'CmsParagraph_ID', 'CmsParagraph', 'CmsParagraph_ID'));
        $this->addDefaultColumnsToTable($table);
        return $this->query($table);
    }

    public function updateTableCmsMenu()
    {
        $table = $this->getTableStatement('CmsMenu');
        $this->addColumnToTable($table, new Integer('CmsMenu_ID'))
            ->setOption('AUTO_INCREMENT', true);
        $this->addColumnToTable($table, new Integer('CmsMenu_ID_Parent'));
        $this->addColumnToTable($table, new Integer('CmsSite_ID'));
        $this->addConstraintToTable($table, new PrimaryKey('CmsMenu_ID'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'CmsSite_ID', 'CmsSite', 'CmsSite_ID'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'CmsMenu_ID_Parent', 'CmsMenu', 'CmsMenu_ID'));
        $this->addDefaultColumnsToTable($table);
        return $this->query($table);
    }

}
