<?php

namespace Base\Database\Updater;


use Laminas\Db\Sql\Ddl\Column\Boolean;
use Laminas\Db\Sql\Ddl\Column\Integer;
use Laminas\Db\Sql\Ddl\Column\Varchar;
use Laminas\Db\Sql\Ddl\Constraint\ForeignKey;
use Laminas\Db\Sql\Ddl\Constraint\PrimaryKey;
use Laminas\Db\Sql\Ddl\Constraint\UniqueKey;
use Laminas\Db\Sql\Ddl\Index\Index;

class SchemaUpdater extends AbstractUpdater
{

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

        $this->addConstraintToTable($table, new PrimaryKey('Person_ID'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'Person_ID', 'Person', 'Person_ID', 'CASCADE'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'UserState_Code', 'UserState', 'UserState_Code'));
        $this->addConstraintToTable($table, new UniqueKey('User_Username'));
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
        $this->addConstraintToTable($table, new ForeignKey(null, 'UserRole_ID', 'UserRole', 'UserRole_ID'));
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
        $this->addConstraintToTable($table, new ForeignKey(null, 'UserRole_ID', 'UserRole', 'UserRole_ID'));
        $this->addConstraintToTable($table, new ForeignKey(null, 'UserPermission_Code', 'UserPermission', 'UserPermission_Code'));
        $this->addDefaultColumnsToTable($table);

        return $this->query($table);
    }

}
