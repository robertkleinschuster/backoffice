<?php

namespace Backoffice\Database\Updater;


use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\AbstractSql;
use Laminas\Db\Sql\Ddl\AlterTable;
use Laminas\Db\Sql\Ddl\Column\Boolean;
use Laminas\Db\Sql\Ddl\Column\Column;
use Laminas\Db\Sql\Ddl\Column\Integer;
use Laminas\Db\Sql\Ddl\Column\Varchar;
use Laminas\Db\Sql\Ddl\Constraint\ForeignKey;
use Laminas\Db\Sql\Ddl\Constraint\PrimaryKey;
use Laminas\Db\Sql\Ddl\Constraint\UniqueKey;
use Laminas\Db\Sql\Ddl\CreateTable;
use Laminas\Db\Sql\Sql;

class SchemaUpdater extends AbstractUpdater
{

    /**
     * @var array
     */
    private $existingTableList;

    /**
     * SchemaUpdater constructor.
     * @param $adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
        $this->existingTableList = $this->metadata->getTableNames($adapter->getCurrentSchema());
    }


    /**
     * @param string $table
     * @return AlterTable|CreateTable
     */
    protected function getTableStatement(string $table)
    {
        if (!in_array($table, $this->existingTableList)) {
            $personTable = new CreateTable($table);
        } else {
            $personTable = new AlterTable($table);
        }
        return $personTable;
    }

    /**
     * @param string $tableName
     * @param AbstractSql $table
     * @param Column $column
     * @throws \Exception
     */
    protected function addColumnToTable(AbstractSql $table, Column $column)
    {
        if ($table instanceof CreateTable) {
            $table->addColumn($column);
        }
        if ($table instanceof AlterTable) {
            $columns = $this->metadata->getColumnNames($table->getRawState(AlterTable::TABLE), $this->adapter->getCurrentSchema());
            if (!in_array($column->getName(), $columns)) {
                $table->addColumn($column);
            } else {
                $table->changeColumn($column->getName(), $column);
            }
        }
        return $column;
    }



    public function updateTablePerson()
    {
        $table = $this->getTableStatement('Person');
        $personId = new Integer('Person_ID');
        $personId->setOption('AUTO_INCREMENT', true);
        $this->addColumnToTable($table, $personId);
        $this->addColumnToTable($table, new Varchar('Person_Firstname', 255));
        $this->addColumnToTable($table, new Varchar('Person_Lastname', 255));
        if ($table instanceof CreateTable) {
            $table->addConstraint(new PrimaryKey('Person_ID'));
        }
        return $this->query($table);
    }

    public function updateTableUserState()
    {
        $table = $this->getTableStatement('UserState');
        $this->addColumnToTable($table, new Varchar('UserState_Code', 255));
        $this->addColumnToTable($table, new Boolean('UserState_Active', 255));
        if ($table instanceof CreateTable) {
            $table->addConstraint(new PrimaryKey('UserState_Code'));
        }
        return $this->query($table);
    }

    public function updateTableUser()
    {
        $table = $this->getTableStatement('User');
        $this->addColumnToTable($table, new Integer('Person_ID'));
        $this->addColumnToTable($table, new Varchar('UserState_Code', 255));
        $this->addColumnToTable($table, new Varchar('User_Username', 255))
            ->addConstraint(new UniqueKey());
        $this->addColumnToTable($table, new Varchar('User_Displayname', 255));
        $this->addColumnToTable($table, new Varchar('User_Password', 255));
        if ($table instanceof CreateTable) {
            $table->addConstraint(new PrimaryKey('Person_ID'));
            $table->addConstraint(new ForeignKey('FK_User_Person', 'Person_ID', 'Person', 'Person_ID'));
            $table->addConstraint(new ForeignKey('FK_User_UserState', 'UserState_Code', 'UserState', 'UserState_Code'));

        }
        return $this->query($table);
    }


    public function updateTableUserRole()
    {
        $table = $this->getTableStatement('UserRole');
        $this->addColumnToTable($table, new Varchar('UserRole_Code', 255));
        $this->addColumnToTable($table, new Boolean('UserRole_Active'));
        if ($table instanceof CreateTable) {
            $table->addConstraint(new PrimaryKey('UserRole_Code'));
        }
        return $this->query($table);
    }

    public function updateTableUser_UserRole()
    {
        $table = $this->getTableStatement('User_UserRole');
        $this->addColumnToTable($table, new Integer('Person_ID'));
        $this->addColumnToTable($table, new Varchar('UserRole_Code', 255));
        if ($table instanceof CreateTable) {
            $table->addConstraint(new PrimaryKey(['Person_ID', 'UserRole_Code']));
            $table->addConstraint(new ForeignKey('FK_User_UserRole_User', 'Person_ID', 'User', 'Person_ID'));
            $table->addConstraint(new ForeignKey('FK_User_UserRole_UserRole', 'UserRole_Code', 'UserRole', 'UserRole_Code'));
        }
        return $this->query($table);

    }

    public function updateTableUserPermission()
    {
        $table = $this->getTableStatement('UserPermission');
        $this->addColumnToTable($table, new Varchar('UserPermission_Code', 255));
        $this->addColumnToTable($table, new Boolean('UserPermission_Active'));
        if ($table instanceof CreateTable) {
            $table->addConstraint(new PrimaryKey('UserPermission_Code'));
        }
        return $this->query($table);
    }

    public function updateTableUserRole_UserPermission()
    {
        $table = $this->getTableStatement('UserRole_UserPermission');
        $this->addColumnToTable($table, new Varchar('UserRole_Code', 255));
        $this->addColumnToTable($table, new Varchar('UserPermission_Code', 255));
        if ($table instanceof CreateTable) {
            $table->addConstraint(new PrimaryKey(['UserRole_Code', 'UserPermission_Code']));
            $table->addConstraint(new ForeignKey('FK_UserRole_UserPermission_UserRole', 'UserRole_Code', 'UserRole', 'UserRole_Code'));
            $table->addConstraint(new ForeignKey('FK_UserRole_UserPermission_UserPermission', 'UserPermission_Code', 'UserPermission', 'UserPermission_Code'));
        }
        return $this->query($table);
    }

}