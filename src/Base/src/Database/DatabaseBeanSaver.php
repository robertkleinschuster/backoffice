<?php


namespace Base\Database;


use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Sql;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\BeanProcessor\AbstractBeanSaver;

class DatabaseBeanSaver extends AbstractBeanSaver implements AdapterAwareInterface
{

    use AdapterAwareTrait;
    use DatabaseInfoTrait;

    /**
     * @var int
     */
    private ?int $personId = null;

    /**
     * DatabaseBeanSaver constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->setDbAdapter($adapter);
    }

    /**
     * @return int
     */
    public function getPersonId(): int
    {
        return $this->personId;
    }

    /**
     * @param int $personId
     *
     * @return $this
     */
    public function setPersonId(int $personId): self
    {
        $this->personId = $personId;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasPersonId(): bool
    {
        return $this->personId !== null;
    }


    /**
     * @param BeanInterface $bean
     * @return bool
     * @throws \Exception
     */
    protected function saveBean(BeanInterface $bean): bool
    {
        $result = [];
        foreach ($this->getTable_List() as $table) {
            if ($this->beanExistsUnique($bean, $table)) {
                $result[] = $this->update($bean, $table);
            } else {
                $result[] = $this->insert($bean, $table);
            }
        }
        return !in_array(false, $result) && count($result) > 0;
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     * @throws \Exception
     */
    protected function deleteBean(BeanInterface $bean): bool
    {
        $result_List = [];
        $tableList = $this->getTable_List();
        $tableList = array_reverse($tableList);
        foreach ($tableList as $table) {
            $deletedata = $this->getDataFromBean($bean, $table);
            // ensure only a single row is deleted
            if (count($deletedata) && $this->count($table, $deletedata) === 1) {
                $sql = new Sql($this->adapter);
                $delete = $sql->delete($table);
                $delete->where($deletedata);
                $result = $this->adapter->query($sql->buildSqlString($delete), $this->adapter::QUERY_MODE_EXECUTE);
                $result_List[] = $result->getAffectedRows() > 0;
            }
        }
        return !in_array(false, $result_List, true) && count($result_List) > 0;
    }


    /**
     * @param BeanInterface $bean
     * @param string $table
     * @return bool
     * @throws \Exception
     */
    protected function insert(BeanInterface $bean, string $table): bool
    {
        $insertdata = $this->getDataFromBean($bean, $table);
        if (count($insertdata)) {
            $dateTime = new \DateTime();
            $insertdata['Timestamp_Create'] = $dateTime->format('Y-m-d H:i:s');
            $insertdata['Timestamp_Edit'] = $dateTime->format('Y-m-d H:i:s');
            if ($this->hasPersonId()) {
                $insertdata['Person_ID_Create'] = $this->getPersonId();
                $insertdata['Person_ID_Edit'] = $this->getPersonId();
            }

            $sql = new Sql($this->adapter);
            $insert = $sql->insert($table);
            $insert->columns(array_keys($insertdata));
            $insert->values(array_values($insertdata));

            $result = $this->adapter->query($sql->buildSqlString($insert), $this->adapter::QUERY_MODE_EXECUTE);
            $keyField_List = $this->getKeyField_List();
            if (count($keyField_List) == 1) {
                foreach ($keyField_List as $field) {
                    $bean->setData($field, $result->getGeneratedValue());
                }
            }
            return $result->getAffectedRows() > 0;
        }

        return false;
    }


    /**
     * @param BeanInterface $bean
     * @param string $table
     * @return bool
     * @throws \Exception
     */
    protected function update(BeanInterface $bean, string $table): bool
    {
        $data = $this->getDataFromBean($bean, $table);
        // Ensure only a single row is changed
        if (count($data) && $this->beanExistsUnique($bean, $table)) {
            $dateTime = new \DateTime();
            $data['Timestamp_Edit'] = $dateTime->format('Y-m-d H:i:s');
            if ($this->hasPersonId()) {
                $data['Person_ID_Edit'] = $this->getPersonId();
            }
            $sql = new Sql($this->adapter);
            $update = $sql->update($table);
            foreach ($this->getKeyField_List() as $field) {
                if ($bean->hasData($field)) {
                    $update->where([$this->getJoinField($field) => $bean->getData($field)]);
                }
            }
            $update->set($data);
            $result = $this->adapter->query($sql->buildSqlString($update), $this->adapter::QUERY_MODE_EXECUTE);
            return $result->getAffectedRows() > 0;
        }

        return false;
    }


    /**
     * @param BeanInterface $bean
     * @param string $table
     * @return bool
     * @throws \Exception
     */
    protected function beanExistsUnique(BeanInterface $bean, string $table): bool
    {
        if (count($this->getKeyDataFromBean($bean, $table))) {
            return $this->count($table, $this->getKeyDataFromBean($bean, $table)) === 1;
        } else {
            return false;
        }
    }

    /**
     * @param BeanInterface $bean
     * @param string|null $table
     * @param bool $includeKeys
     * @return array
     * @throws \Exception
     */
    protected function getDataFromBean(BeanInterface $bean, string $table = null, bool $includeKeys = true): array
    {
        $formatter = new DatabaseBeanFormatter();
        $data = [];
        foreach ($this->getField_List($table) as $field) {
            if ($bean->hasData($field)) {
                $data[$this->getColumn($field)] = $formatter->format($bean)->getValue($field);
            }
        }
        if ($includeKeys) {
            foreach ($this->getKeyField_List() as $field) {
                if ($bean->hasData($field)) {
                    $data[$this->getJoinField($field)] = $formatter->format($bean)->getValue($field);
                }
            }
        }
        return $data;
    }

    /**
     * @param BeanInterface $bean
     * @param string $table
     * @throws \Exception
     */
    protected function getKeyDataFromBean(BeanInterface $bean, string $table): array
    {
        $formatter = new DatabaseBeanFormatter();
        $data = [];
        foreach ($this->getField_List($table) as $field) {
            if ($bean->hasData($field) && in_array($field, $this->getKeyField_List())) {
                $data[$this->getColumn($field)] = $formatter->format($bean)->getValue($field);
            }
        }
        return $data;
    }

    /**
     * @param string $table
     * @param array $data
     * @return int|mixed
     */
    protected function count(string $table, array $data): int
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select($table);
        $select->where($data);
        $select->columns(['COUNT' => new Expression('COUNT(*)')], false);
        $result = $this->adapter->query($sql->buildSqlString($select), $this->adapter::QUERY_MODE_EXECUTE);
        return intval($result->current()['COUNT'] ?? 0);
    }


}
