<?php


namespace Base\Database;


use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Adapter\Driver\StatementInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Join;
use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\Predicate;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Where;
use NiceshopsDev\Bean\BeanException;
use NiceshopsDev\Bean\BeanFinder\AbstractBeanLoader;
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
     * @var string[]
     */
    private $where_Map;

    /**
     * @var string[]
     */
    private $exclude_Map;

    /**
     * @var array[]
     */
    private $like_Map;

    /**
     * @var ResultSet
     */
    private $result = null;

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
    public function __construct(Adapter $adapter)
    {
        $this->setDbAdapter($adapter);
        $this->where_Map = [];
        $this->exclude_Map = [];
        $this->like_Map = [];
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
     * @param string $field
     * @param array $valueList
     * @return DatabaseBeanLoader|void
     * @throws \Exception
     */
    public function initByValueList(string $field, array $valueList)
    {
        return $this->filterValue($field, $valueList);
    }

    /**
     * @param string $field
     * @param $value
     * @param string $logic
     * @return DatabaseBeanLoader
     * @throws \Exception
     */
    public function filterValue(string $field, $value, $logic = Predicate::OP_AND)
    {
        if ($this->hasField($field)) {
            $this->where_Map[$logic]["{$this->getTable($field)}.{$this->getColumn($field)}"] = $value;
        }
        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @param string $logic
     * @throws \Exception
     */
    public function excludeValue(string $field, $value, $logic = Predicate::OP_AND)
    {
        if ($this->hasField($field)) {
            $this->exclude_Map[$logic]["{$this->getTable($field)}.{$this->getColumn($field)}"] = $value;
        }
    }


    /**
     * @param array $idMap
     * @throws \Exception
     */
    public function initByIdMap(array $idMap)
    {
        foreach ($idMap as $field => $value) {
            if (!empty($value)) {
                $this->filterValue($field, $value);
            }
        }
    }

    /**
     * @param string $str
     * @param array $fields
     * @return $this
     */
    public function addLike(string $str, ...$fields)
    {
        $this->like_Map[$str] = $fields;
        return $this;
    }



    /**
     * @param Select $select
     * @throws \Exception
     */
    protected function handleJoins(Select $select)
    {
        $self = $select->getRawState(Select::TABLE);
        foreach ($this->getField_List() as $field) {
            $table = $this->getTable($field);
            if ($table !== $self) {
                $joins = $select->getRawState(Select::JOINS);
                if (!in_array($table, array_column($joins->getJoins(), 'name'))) {
                    $column = $this->getColumn($this->getJoinField($field));
                    $columnSelf = $this->getColumn($this->getJoinFieldSelf($field));
                    $tableSelf = $this->getJoinTableSelf($field, $self);
                    if ($this->hasJoinInfo($table)) {
                        $select->join($table, $this->getJoinOn($table), [], $this->getJoinType($table));
                    } else {
                        $select->join($table, "$tableSelf.$columnSelf = $table.$column", []);
                    }
                }
            }
        }
    }

    /**
     * @param Select $select
     */
    protected function handleWhere(Select $select)
    {
        foreach ($this->exclude_Map as $logic => $map) {
            foreach ($map as $column => $value) {
                $where = new Predicate();
                $where->notEqualTo($column, $value);
                $select->where($where);
            }
        }

        foreach ($this->where_Map as $logic => $map) {
            $select->where($map, $logic);
        }
    }

    /**
     * @param Select $select
     * @return DatabaseBeanLoader
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
     * @param Select $select
     * @return DatabaseBeanLoader
     */
    protected function handleLike(Select $select)
    {
        foreach ($this->like_Map as $str => $like) {
            foreach ($like as $field) {
                $select->where(new Like("{$this->getTable($field)}.{$this->getColumn($field)}", $str), Predicate::OP_OR);
            }
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
        if ($this->result === null) {
            throw new BeanException('Could not fetch data. Run find first.');
        }
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
        $beanData = [];
        foreach ($this->getField_List() as $field) {
            $beanData[$field] = $data["{$this->getTable($field)}.{$this->getColumn($field)}"];
        }
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
        $table_List = $this->getTable_List();
        $select = $sql->select(reset($table_List));
        $this->handleJoins($select);
        $this->handleWhere($select);
        $this->handleLike($select);
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
     * @throws \Exception
     */
    protected function handleSelect(Select $select)
    {
        $columns = [];
        foreach ($this->getField_List() as $field) {
            $columns[] = "{$this->getTable($field)}.{$this->getColumn($field)}";
        }
        $select->columns($columns, false);
    }

    /**
     * @param Select $select
     * @return \Laminas\Db\Adapter\Driver\ResultInterface|StatementInterface|\Laminas\Db\ResultSet\ResultSet|\Laminas\Db\ResultSet\ResultSetInterface|null
     */
    protected function getPreparedStatement(Select $select)
    {
        return $this->adapter->query((new Sql($this->adapter))->buildSqlString($select));
    }

    /**
     * @param string $field
     * @return array
     * @throws \Exception
     */
    public function preloadValueList(string $field): array
    {
        $select = $this->buildSelect(true, false);
        $select->reset(Select::COLUMNS);
        $column = $this->getColumn($field);
        $select->columns([$column]);
        $result = $this->getPreparedStatement($select)->execute();
        $ret = [];
        foreach ($result as $row) {
            $ret[] = $row[$column];
        }
        return $ret;
    }

    /**
     * @return string
     */
    public function getLastQuery(): string
    {
        return $this->adapter->getProfiler()->getLastProfile()['sql'];
    }

}
