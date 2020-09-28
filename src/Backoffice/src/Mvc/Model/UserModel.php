<?php


namespace Backoffice\Mvc\Model;

use Backoffice\Authentication\Bean\UserBeanFinder;
use Backoffice\Authentication\Bean\UserBeanProcessor;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Metadata\Metadata;
use Laminas\Db\Sql\Ddl\Column\Boolean;
use Laminas\Db\Sql\Ddl\Column\Integer;
use Laminas\Db\Sql\Ddl\Column\Varchar;
use Laminas\Db\Sql\Ddl\Constraint\ForeignKey;
use Laminas\Db\Sql\Ddl\Constraint\PrimaryKey;
use Laminas\Db\Sql\Ddl\Constraint\UniqueKey;
use Laminas\Db\Sql\Ddl\CreateTable;
use Laminas\Db\Sql\Delete;


class UserModel extends BaseModel
{
    public function initUserTable() {
            $metadata = new Metadata($this->getDbAdpater());
            $tableNames = $metadata->getTableNames();

            if (!in_array('Person', $tableNames)) {
                $personTable = new CreateTable('Person');
                $personID = new Integer('Person_ID');
                $personID->setOption('AUTO_INCREMENT', true);
                $personID->addConstraint(new PrimaryKey());
                $personTable->addColumn($personID);
                $personTable->addColumn(new Varchar('Person_Firstname', 250));
                $personTable->addColumn(new Varchar('Person_Lastname', 250));
                $this->getDbAdpater()->query($personTable->getSqlString($this->getDbAdpater()->getPlatform()), Adapter::QUERY_MODE_EXECUTE);
            }

            if (!in_array('User', $tableNames)) {
                $userTable = new CreateTable('User');
                $personID = new Integer('Person_ID');
                $userTable->addColumn($personID);
                $username = new Varchar('User_Username', 250);
                $userTable->addColumn($username);
                $userTable->addColumn(new Varchar('User_Password', 250));
                $userTable->addColumn(new Varchar('User_Displayname', 250));
                $userTable->addColumn(new Boolean('User_Active', 250));
                $userTable->addConstraint(new UniqueKey('User_Username'));
                $userTable->addConstraint(new ForeignKey('FK_Person_ID', 'Person_ID', 'Person', 'Person_ID'));
                $this->getDbAdpater()->query($userTable->getSqlString($this->getDbAdpater()->getPlatform()), Adapter::QUERY_MODE_EXECUTE);
            }
    }

    /**
     * @return \Backoffice\Authentication\Bean\UserBeanList
     * @throws \NiceshopsDev\Bean\BeanException
     */
    public function getUserBeanList() {
        $factory = new UserBeanFinder($this->getDbAdpater());
        $factory->find();
        return $factory->getBeanList();
    }

    /**
     * @param array $idMap
     * @return \Backoffice\Authentication\Bean\UserBean
     * @throws \NiceshopsDev\Bean\BeanException
     */
    public function getUserBean(array $idMap = null) {
        $finder = new UserBeanFinder($this->getDbAdpater());
        if (null !== $idMap) {
            $finder->getLoader()->initByIdMap($idMap);
            $finder->find();
            return $finder->getBean();
        } else {
            $bean = $finder->getFactory()->createBean();
            return $bean;
        }

    }

    /**
     * @param array $attribute_Map
     * @param array|null $idMap
     * @throws \NiceshopsDev\Bean\BeanException
     */
    public function submitUser(array $attribute_Map, array $idMap = null)
    {
        if (trim($attribute_Map['User_Password']) == '') {
            unset($attribute_Map['User_Password']);
        } else {
            $attribute_Map['User_Password'] = password_hash($attribute_Map['User_Password'], PASSWORD_BCRYPT);
        }

        $bean = $this->getUserBean($idMap);
        $bean->setFromArray($attribute_Map);

        $finder = new UserBeanFinder($this->getDbAdpater());
        $beanList = $finder->getFactory()->createBeanList();
        $beanList->addBean($bean);

        $processor = new UserBeanProcessor($this->getDbAdpater());
        $processor->setBeanList($beanList);
        $result = $processor->process();
        $this->getValidationHelper()->addErrorFieldMap($processor->getValidationHelper()->getErrorFieldMap());
        return $result;
    }

    /**
     * @param array $idMap
     * @return int
     * @throws \NiceshopsDev\Bean\BeanException
     */
    public function deleteUser(array $idMap) {
        $bean = $this->getUserBean($idMap);
        $delete = new Delete('User');
        $delete->where(["Person_ID" => $bean->getData('Person_ID')]);
        $result = $this->adapter->query($delete->getSqlString($this->adapter->getPlatform()))->execute();
        return $result->getAffectedRows();
    }

}
