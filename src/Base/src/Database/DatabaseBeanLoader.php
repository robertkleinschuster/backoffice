<?php


namespace Base\Database;


use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Adapter\Driver\StatementInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Predicate\Predicate;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanLoader;
use NiceshopsDev\Bean\BeanFinder\BeanLoaderInterface;
use NiceshopsDev\Bean\BeanInterface;
use NiceshopsDev\NiceCore\Attribute\AttributeTrait;
use NiceshopsDev\NiceCore\Option\OptionTrait;

class DatabaseBeanLoader extends AbstractBeanLoader implements AdapterAwareInterface
{
    use OptionTrait;
    use AttributeTrait;
    use AdapterAwareTrait;
    use DatabaseInfoTrait;

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
     * @var string[]
     */
    private $group_Map;

    /**
     * @var string[]
     */
    private $select_Map;

    /**
     * @var ResultSet
     */
    private $result;

    /**
     * @var string[]
     */
    private $fieldColumn_Map;

    /**
     * @var int
     */
    private $limit;


    /**
     * @var
     */
    private $offset;


    /**
     * UserBeanLoader constructor.
     * @param Adapter $adapter
     * @param string $table
     */
    public function __construct(Adapter $adapter, string $table)
    {
        $this->setDbAdapter($adapter);
        $this->table = $table;
        $this->setTableList([$table]);
        $this->join_Map = [];
        $this->where_Map = [];
        $this->group_Map = [];
        $this->select_Map = [];
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
     * @return string[]
     * @throws \Exception
     */
    public function getFieldColumnMap(): array
    {
        if (null == $this->fieldColumn_Map) {
            throw new \Exception('Field column map not initialized.');
        }
        return $this->fieldColumn_Map;
    }

    /**
     * @param string[] $fieldColumn_Map
     * @return DatabaseBeanLoader
     */
    public function setFieldColumnMap(array $fieldColumn_Map)
    {
        $this->fieldColumn_Map = $fieldColumn_Map;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasLimit(): bool
    {
        return $this->limit !== null;
    }

    /**
    * @return int
    */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
    * @param int $offset
    *
    * @return $this
    */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasOffset(): bool
    {
        return $this->offset !== null;
    }


    /**
     * @param string $table
     * @param string $column
     * @param string|null $remoteColumn
     */
    public function addJoin(string $table, string $column, ?string $remoteColumn = null)
    {
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
     * @return DatabaseBeanLoader
     * @throws \Exception
     */
    public function addWhere(string $key, $value, string $table = null, $logic = Predicate::OP_AND)
    {
        if ($this->checkColumnExists($key)) {
            if (null === $table) {
                $table = $this->getTable();
            }
            $this->where_Map[$logic]["$table.$key"] = $value;
        }
        return $this;
    }

    /**
     * @param string $key
     * @param string|null $table
     * @return $this
     * @throws \Exception
     */
    public function addGroup(string $key, string $table = null)
    {
        if ($this->checkColumnExists($key)) {
            if (null === $table) {
                $table = $this->getTable();
            }
            $this->group_Map[] = "$table.$key";
        }
        return $this;
    }

    /**
     * @param string $key
     * @param string|null $table
     * @return $this
     * @throws \Exception
     */
    public function addSelect(string $key, string $table = null)
    {
        if ($this->checkColumnExists($key)) {
            if (null === $table) {
                $table = $this->getTable();
            }
            $this->select_Map[$key] = "$table.$key";
        }
        return $this;
    }

    public function checkColumnExists(string $column)
    {
        return in_array($column, $this->getFieldColumnMap());
    }



    /**
     * @param array $idMap
     * @throws \Exception
     */
    public function initByIdMap(array $idMap)
    {
        foreach ($idMap as $key => $value) {
            if ($value) {
                $explode = explode('.', $key);
                if (count($explode) == 1) {
                    $this->addWhere($explode[0], $value);
                } elseif (count($explode) == 2) {
                    $this->addWhere($explode[1], $value, $explode[0]);
                }
            }
        }
    }


    /**
     * @param string $field
     * @param array $valueList
     * @return DatabaseBeanLoader|void
     * @throws \Exception
     */
    public function initByValueList(string $field, array $valueList)
    {
        return $this->addWhere($this->getFieldColumnMap()[$field], $valueList);
    }

    /**
     * @param Select $select
     */
    protected function handleJoins(Select $select)
    {
        $self = $select->getRawState(Select::TABLE);
        foreach ($this->join_Map as $table => $item) {
            $local = $item['local'];
            $remote = $item['remote'];
            $select->join($table, "$self.$local = $table.$remote", []);
        }
    }

    /**
     * @param Select $select
     */
    protected function handleWhere(Select $select)
    {
        foreach ($this->where_Map as $logic => $map) {
            $select->where($map, $logic);
        }
    }

    /**
     * @param Select $select
     */
    protected function handleGroup(Select $select)
    {
        foreach ($this->group_Map as $group) {
            $select->group($group);
        }
    }

    /**
     * @param Select $select
     */
    protected function handleLimit(Select $select)
    {
        if ($this->hasLimit()) {
            $select->limit($this->getLimit());
        }
        if ($this->hasOffset()) {
            $select->offset($this->getOffset());
        }
        return $this;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit(int $limit, int $offset)
    {
        $this->setLimit($limit);
        $this->setOffset($offset);
        return $this;
    }


    /**
     * @return int
     */
    public function count(): int
    {
        $select = $this->buildSelect();
        $select->columns(['COUNT' => new Expression('COUNT(*)')], false);
        $result = $this->getPreparedStatement($select)->execute();
        return $result->current()['COUNT'] ?? 0;
    }

    /**
     * @return int
     */
    public function find(): int
    {
        return $this->getResult()->count();
    }

    /**
     * @param bool $limit
     * @return \Laminas\Db\Adapter\Driver\ResultInterface|ResultSet
     */
    protected function getResult()
    {
        if (null === $this->result) {
            $this->result = $this->getPreparedStatement($this->buildSelect(true, true))->execute();
        }
        return $this->result;
    }

    /**
     * @return bool
     */
    public function fetch(): bool
    {
        if ($this->result->key() < $this->result->count() - 1) {
            $this->result->next();
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function data(): array
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
        $parser = new DatabaseBeanParser();
        $data = $this->data();
        $beanData = array_intersect_key($data, array_flip($this->getFieldColumnMap()));
        return $parser->parse($beanData, $bean)->toBean();
    }


    /**
     * @param bool $limit
     * @param bool $selectColumns
     * @return Select
     */
    protected function buildSelect(bool $limit = false, bool $selectColumns = false): Select
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select($this->getTable());
        $this->handleJoins($select);
        $this->handleWhere($select);
        $this->handleGroup($select);
        if ($limit) {
            $this->handleLimit($select);
        }
        if ($selectColumns) {
            $this->handleSelect($select);
        }
        return $select;
    }

    /**
     * @param $select
     */
    protected function handleSelect(Select $select)
    {
        if (count($this->select_Map)) {
            $select->columns($this->select_Map, false);
        }
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

    public function preloadValueList(string $field): array
    {
        $select = $this->buildSelect(true, false);
        $select->reset(Select::COLUMNS);
        $column = $this->getFieldColumnMap()[$field];
        $select->columns([$column]);
        $result = $this->getPreparedStatement($select)->execute();
        $ret = [];
        foreach ($result as $row) {
            $ret[] = $row[$column];
        }
        return $ret;
    }

    public function key()
    {
        return $this->getResult()->key();
    }

    public function rewind()
    {
    }


}
