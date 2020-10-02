<?php


namespace Backoffice\Database;


use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Adapter\Driver\StatementInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Predicate\Predicate;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use NiceshopsDev\Bean\AbstractBaseBean;
use NiceshopsDev\Bean\BeanFinder\BeanLoaderInterface;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\Bean\Database\DatabaseBeanInterface;
use NiceshopsDev\NiceCore\Attribute\AttributeTrait;
use NiceshopsDev\NiceCore\Option\OptionTrait;

class DatabaseBeanLoader implements BeanLoaderInterface, AdapterAwareInterface
{
    use OptionTrait;
    use AttributeTrait;
    use AdapterAwareTrait;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string[]
     */
    private $join_Map;

    /**
     * @var string[]
     */
    private $where_Map;

    /**
     * @var ResultSet
     */
    private $result;

    /**
     * @var array
     */
    private $column_List;


    /**
     * UserBeanLoader constructor.
     * @param Adapter $adapter
     * @param string $table
     */
    public function __construct(Adapter $adapter, string $table)
    {
        $this->setDbAdapter($adapter);
        $this->table = $table;
        $this->join_Map = [];
        $this->where_Map = [];
        $this->column_List = $this->getMetadata()->getColumnNames($this->getTable(), $this->adapter->getCurrentSchema());
    }


    protected function getMetadata()
    {
        return \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($this->adapter);
    }

    /**
     * @param string $table
     * @return DatabaseBeanLoader
     */
    public function setTable(string $table)
    {
        $this->table = $table;
        return $this;
    }


    /**
     * @return string
     */
    protected function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param string $table
     * @param string $column
     * @param string|null $remoteColumn
     */
    public function addJoin(string $table, string $column, ?string $remoteColumn = null)
    {
        $this->column_List = array_merge($this->column_List, $this->getMetadata()->getColumnNames($table, $this->adapter->getCurrentSchema()));
        if (null === $remoteColumn) {
            $remoteColumn = $column;
        }
        $this->join_Map[$table] = [
            'local' => $column,
            'remote' => $remoteColumn
        ];
    }

    /**
     * @param string $key
     * @param $value
     * @param string $table
     * @param string $logic
     * @throws \Exception
     */
    public function addWhere(string $key, $value, string $table = null, $logic = Predicate::OP_AND) {
        if (in_array($key, $this->column_List)) {
            if (null === $table) {
                $table = $this->getTable();
            }
            $this->where_Map[$logic]["$table.$key"] = $value;
        } else {
            throw new \Exception('Invalid key');
        }
    }


    /**
     * @param array $idMap
     */
    public function initByIdMap(array $idMap) {
        foreach ($idMap as $key => $value) {
            if ($value) {
                $explode = explode('.', $key);
                if (count($explode) == 1) {
                    $this->addWhere($explode[0], $value);
                } elseif (count($explode) == 2) {
                    $this->addWhere($explode[1], $value, $explode[0]);
                } else {
                    throw new \Exception('Invalid key');
                }
            }
        }
    }

    /**
     * @param Select $select
     */
    protected function handleJoins(Select $select) {
        $self = $select->getRawState(Select::TABLE);
        foreach ($this->join_Map as $table => $item) {
            $local = $item['local'];
            $remote = $item['remote'];
            $select->join($table, "$self.$local = $table.$remote");
        }
    }

    /**
     * @param Select $select
     */
    protected function handleWhere(Select $select) {
        foreach ($this->where_Map as $logic => $map) {
            $select->where($map, $logic);
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->getResult()->count();
    }

    /**
     * @return int
     */
    public function find(): int
    {
        return $this->getResult()->count();
    }

    protected function getResult(){
        if (null === $this->result) {
            $this->result = $this->getPreparedStatement($this->buildSelect())->execute();
        }
        return $this->result;
    }

    /**
     * @return bool
     */
    public function fetch(): bool
    {
        if ($this->result->key() < $this->result->count() -1) {
            $this->result->next();
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getRow(): array
    {
        return $this->result->current();
    }

    /**
     * @param BeanInterface $bean
     * @return BeanInterface
     * @throws \Exception
     */
    public function initializeBeanWithData(BeanInterface $bean): BeanInterface
    {
        $data = $this->getRow();
        if ($bean instanceof DatabaseBeanInterface) {
            foreach ($bean->getDatabaseFieldName_Map() as $name => $dbColumn) {
                if (isset($data[$dbColumn])) {
                    $bean->setData($name, $this->convertValueFromDatabase($data[$dbColumn], $bean->getDataType($name)));
                }
            }
        }
       return $bean;
    }


    /**
     * @param $value
     * @param string $type
     * @return bool|\DateTime|false|int|mixed|string|null
     * @throws \Exception
     */
    protected function convertValueFromDatabase($value, string $type)
    {
        if ($value === null || $value === '') {
            return null;
        }
        switch ($type) {
            case AbstractBaseBean::DATA_TYPE_STRING:
                return strval($value);
            case AbstractBaseBean::DATA_TYPE_BOOL:
                if ($value === 'true') {
                    return true;
                } elseif ($value === 'false') {
                    return false;
                }
            case AbstractBaseBean::DATA_TYPE_INT:
                return intval($value);
            case AbstractBaseBean::DATA_TYPE_FLOAT:
                return boolval($value);
            case AbstractBaseBean::DATA_TYPE_ARRAY:
                return json_decode($value);
            case AbstractBaseBean::DATA_TYPE_DATE:
            case AbstractBaseBean::DATA_TYPE_DATETIME_PHP:
                return \DateTime::createFromFormat('Y-m-d H:i:s', $value);

        }
        throw new \Exception("Unabled to convert $type from db.");
    }

    /**
     * @return Select
     */
    protected function buildSelect(): Select
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select($this->getTable());
        $this->handleJoins($select);
        $this->handleWhere($select);
        return $select;
    }

    /**
     * @param Select $select
     * @return \Laminas\Db\Adapter\Driver\ResultInterface|StatementInterface|\Laminas\Db\ResultSet\ResultSet|\Laminas\Db\ResultSet\ResultSetInterface|null
     */
    protected function getPreparedStatement(Select $select)
    {
        $sql = new Sql($this->adapter);
        return $this->adapter->query($sql->buildSqlString($select));
    }
}
