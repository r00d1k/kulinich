<?php

/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Storage_DbSelect_Abstract
 * @subpackage Storage_DbSelect
 * @use Core_Entity_Storage_Interface
 * @rquire Zend_Db, Zend_Db_Select
 *
 * Adapter to work with database based on Zend_Db_Select
 */
abstract class Core_Entity_Storage_DbSelect_Abstract implements Core_Entity_Storage_Interface
{

    protected $_logger = null;

    /**
     * Join type INNER
     */

    const JOIN_INNER = 'inner';

    /**
     * Join Type Left.
     */
    const JOIN_LEFT = 'left';
    const NAME      = 'name';
    const SEQUENCE  = 'sequence';

    protected static $_manualTransactions       = true;
    protected static $_manualTransactionStarted = false;

    /**
     * Table Name.
     *
     * @var string
     */
    protected $_name     = null;
    protected $_sequence = true;

    /**
     * Mapper Class Name.
     *
     * @var string
     */
    protected $_mapperClass = null;

    /**
     * Instance of mapper Class.
     *
     * @var Core_Entity_Mapper_Abstract
     */
    protected $_mapperInstance = null;

    /**
     * Constructor.
     *
     * @param array|Zend_Config $options Options.
     */
    public function __construct($options = null)
    {
        $this->_setOptions($options);
        if(APPLICATION_ENV == 'development')
        {
            $this->_logger = Core_Log_Manager::getInstance()->getLog('default');
        }
    }

    /**
     * Maximal Nesting level for joined relations.
     *
     * @var integer
     */
    protected static $_joinNestingLevel = 2;

    /**
     * Sets maximal Nesting level for joined relations.
     *
     * @param integer $level Maximal depth from 0(no joins) to 3.
     *
     * @return void
     */
    public static function setNestingLevel($level)
    {
        if($level > 3)
        {
            $level = 3;
        }
        self::$_joinNestingLevel = $level;
    }

    /**
     * Gets a default adapter.
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public static function getDefaultAdapter()
    {
        return Zend_Db_Table::getDefaultAdapter();
    }

    /**
     * Gets a setting.
     *
     * @param string $param Setting name to get.
     *
     * @return mixed
     */
    public function info($param)
    {
        switch($param)
        {
            case self::NAME:
                return $this->_name;
                break;

            case self::SEQUENCE:
                return $this->_sequence;
                break;

            default:
                /**
                 *  Nothing here.
                 */
                break;
        }
    }

    /**
     * This function setting options for current storage instunce.
     *
     * @param array|Zend_Config $options List of options.
     *
     * @return void
     */
    protected function _setOptions($options)
    {
        if($options instanceof Zend_Config)
        {
            $options = $options->toArray();
        }
        if(is_array($options))
        {
            foreach($options as $key => $val)
            {
                switch($key)
                {
                    case self::NAME:
                        $this->_name = $val;
                        break;

                    case self::SEQUENCE:
                        $this->_sequence = (bool)$val;
                        break;

                    default:
                        /**
                         *  Nothing here.
                         */
                        break;
                }
            }
        }
    }

    /**
     * Setting mapper for current storage instance.
     *
     * @param Core_Entity_Mapper_Abstract $instance A mapper instance.
     *
     * @return Core_Entity_Storage_DbSelect_Abstract
     */
    public function setMapperInstance(Core_Entity_Mapper_Abstract $instance)
    {
        $this->_mapperInstance = $instance;
        return $this;
    }

    /**
     * Gets mapper instance asociated with current storge.
     *
     * @return Core_Entity_Mapper_Abstract
     * @throws Core_Entity_Exception On Error.
     */
    public function getMapper()
    {
        if($this->_mapperInstance == null)
        {
            if($this->_mapperClass == null)
            {
                $log = Core_Log_Manager::getInstance()->getLog('default');
                if(!empty($log))
                {
                    $log->log("[" . get_class($this) . "]\t" . 'Mapper class undefined',
                        Zend_Log::WARN);
                }
                throw new Core_Entity_Exception('Mapper class undefined');
            }
            $mapperClass           = $this->_mapperClass;
            $this->_mapperInstance = $mapperClass::getInstance();
        }
        return $this->_mapperInstance;
    }

    /**
     * Writing query to log.
     *
     * @param string|Zend_Db_Select $query  Query.
     * @param string                $method Method name.
     *
     * @return boolean
     */
    protected function _logQuery($query, $method = '')
    {
        if($this->_logger == null)
        {
            return false;
        }
        if($query instanceof Zend_Db_Select)
        {
            $query->assemble();
        }
        if(!empty($method))
        {
            $method = '----::----' . $method;
        }

        $this->_logger->log(
            "\r\n---------------------------   [" . get_class($this->getMapper()) . '----::----' . get_class($this) . $method . "] ----------------------\r\n" .
            str_replace(
                array('SELECT', 'FROM', 'WHERE', 'ORDER BY'),
                array("SELECT\r\n", "\r\nFROM\r\n", "\r\nWHERE\r\n", "\r\nORDER BY\r\n"),
                $query
            ) .
            "\r\n---------------------------------------------------------------------------------------------\r\n",
            Zend_Log::DEBUG
        );
        return true;
    }

    /**
     *  Counting db rows by cindition.
     *
     * @param mixed $condition Condition.
     *
     * @return integer
     */
    public function count($condition = array())
    {
        $select = $this->_filterSelect($this->_getBaseSelect(), $condition);
        $select
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns(
                array(
                    'count(`' . $this->info(Zend_Db_Table::NAME) . '`.`' . $this->getMapper()->map($this->getMapper()->getKeyField()) . '`)'
                )
        );
        $this->_logQuery($select, 'count');
        return self::getDefaultAdapter()->fetchOne($select);
    }

    /**
     * Compiling filter and adding it to select.
     *
     * @param Zend_Db_Select $select Select.
     * @param array|null     $filter Filter.
     *
     * @return Zend_Db_Select
     */
    protected function _filterSelect(Zend_Db_Select $select, $filter = array())
    {
        if(empty($filter))
        {
            return $select;
        }
        $filter = new Core_Entity_Storage_DbSelect_Filter(
            array(
            Core_Entity_Storage_DbSelect_Filter::MAPPER => $this->getMapper(),
            Core_Entity_Storage_DbSelect_Filter::FILTER => $filter
            )
        );
        $select->where($filter->assemble());
        return $select;
    }

    /**
     * Gets a column of data from current storage.
     *
     * @param string       $fieldName Column name alias.
     * @param mixed        $condition In format array(field => array(operation, value)).
     * @param string|array $order     StriНg in format 'alias ASD|DESC|Nothing, alias ASD|DESC|Nothing'.
     * @param integer      $count     Count.
     * @param integer      $offset    Offset.
     *
     * @return array .
     */
    public function fetchCol($fieldName, $condition = array(), $order = null, $count = null, $offset = null)
    {
        $select = $this->_filterSelect($this->_getBaseSelect(), $condition);
        if(is_string($order))
        {
            $order = explode(',', $order);
        }
        if(!empty($order))
        {
            foreach($order as $orderField)
            {
                if(is_string($orderField))
                {
                    $orderField = explode(' ', trim($orderField));
                }
                if(empty($orderField[1]))
                {
                    $orderField[1] = 'ASC';
                }
                $select->order('' . $this->info(self::NAME) . '.' . trim($this->getMapper()->map($orderField[0])),
                    $orderField[1]);
            }
        }
        if($count != null || $offset != null)
        {
            $select->limit($count, $offset);
        }

        $select->reset(Zend_Db_Select::COLUMNS);
        $select->columns(
            array(
                $fieldName => $this->getMapper()->map($fieldName)
            )
        );

        $this->_logQuery($select, 'fetchColl');
        return $select->getAdapter()->fetchCol($select);
    }

    /**
     * Gets a rowset of data from current storage.
     *
     * @param mixed        $condition In format array(field => array(operation, value)).
     * @param string|array $order     StriНg in format 'alias ASD|DESC|Nothing, alias ASD|DESC|Nothing'.
     * @param integer      $count     Count.
     * @param integer      $offset    Offset.
     *
     * @return Core_Entity_Rowset_Abstract
     */
    public function fetchAll($condition = array(), $order = null, $count = null, $offset = null)
    {
        $select = $this->_filterSelect($this->_getBaseSelect(), $condition);
        if(is_string($order))
        {
            $order = explode(',', $order);
        }
        if(!empty($order))
        {
            foreach($order as $orderField)
            {
                if(is_string($orderField))
                {
                    $orderField = explode(' ', trim($orderField));
                }
                if(empty($orderField[1]))
                {
                    $orderField[1] = 'ASC';
                }
                $fieldToOrder = trim($this->getMapper()->map($orderField[0]));
                if(strpos($fieldToOrder, '.') === false)
                {
                    $fieldToOrder = $this->info(self::NAME) . '.' . $fieldToOrder;
                }
                $select->order($fieldToOrder . ' ' . $orderField[1]);
            }
        }
        if($count != null || $offset != null)
        {
            $select->limit($count, $offset);
        }

        $this->_logQuery($select, 'fetchAll');

        /** @var Core_Entity_Rowset_Abstract $rowset  */
        $rowset = $this->getMapper()->getRowsetClass();
        return new $rowset($select->getAdapter()->fetchAll($select),
            $this->getMapper());
    }

    /**
     * Gets a rowset of RAW data from current storage.
     *
     * @param mixed        $condition In format array(field => array(operation, value)).
     * @param string|array $order     StriНg in format 'alias ASD|DESC|Nothing, alias ASD|DESC|Nothing'.
     * @param integer      $count     Count.
     * @param integer      $offset    Offset.
     *
     * @return Core_Entity_Rowset_Abstract
     */
    public function fetchAllAsArray($condition = array(), $order = null, $count = null, $offset = null)
    {
        $select = $this->_filterSelect($this->_getBaseSelect(), $condition);
        if(is_string($order))
        {
            $order = explode(',', $order);
        }
        if(!empty($order))
        {
            foreach($order as $orderField)
            {
                if(is_string($orderField))
                {
                    $orderField = explode(' ', trim($orderField));
                }
                if(empty($orderField[1]))
                {
                    $orderField[1] = 'ASC';
                }
                $select->order('' . $this->info(self::NAME) . '.' . trim($this->getMapper()->map($orderField[0])),
                    $orderField[1]);
            }
        }
        if($count != null || $offset != null)
        {
            $select->limit($count, $offset);
        }

        $this->_logQuery($select, 'fetchAllAsArray');
        return $select->getAdapter()->fetchAll($select);
    }

    /**
     *  Gets a data Row.
     *
     * @param mixed $condition Condition.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function fetchRow($condition)
    {
        $model  = $this->getMapper()->getModelClass();
        $select = $this->_filterSelect($this->_getBaseSelect(), $condition);
        $this->_logQuery($select, 'fetchRow');
        $data   = self::getDefaultAdapter()->fetchRow($select->limit(1));
        if(is_array($data) && !empty($data))
        {
            $data  = self::formatDataRow($data);
        }
        $model = new $model(
            array(
            Core_Entity_Model_Abstract::CONSTRUCT_MAPPER  => $this->getMapper(),
            Core_Entity_Model_Abstract::CONSTRUCT_INITIAL => $data
            )
        );
        return $model;
    }

    /**
     * Gets a maximal value of field.
     *
     * @param string $alias     Field.
     * @param mixed  $condition Filter.
     *
     * @return integer
     */
    public function fetchMax($alias, $condition = null)
    {
        $alias = $this->getMapper()->map($alias);
        if(empty($alias) || !is_string($alias))
        {
            return null;
        }

        $select = new Zend_Db_Select(self::getDefaultAdapter());
        $select->from(
            $this->info(self::NAME),
            array('max' => new Zend_Db_Expr('MAX(' . $alias . ')'))
        );
        $select = $this->_filterSelect($this->_getBaseSelect(), $condition);
        $this->_logQuery($select, 'fetchMax');
        return self::getDefaultAdapter()->fetchOne($select);
    }

    /**
     * Formating row data.
     *
     * @param mixed $row Row data.
     *
     * @return array
     */
    public static function formatDataRow($row)
    {
        $data = array();
        foreach($row as $path => $value)
        {
            $path = explode(':', $path);
            switch(count($path))
            {
                case 1:
                    $data[$path[0]] = $value;
                    break;

                case 2:
                    $data[$path[0]][$path[1]] = $value;
                    break;

                case 3:
                    $data[$path[0]][$path[1]][$path[2]] = $value;
                    break;

                case 4:
                    $data[$path[0]][$path[1]][$path[2]][$path[3]] = $value;
                    break;

                default:
                    /**
                     * Nothing here.
                     */
                    break;
            }
        }
        return $data;
    }

    protected $_baseSelect = null;

    /**
     *  Gets a base select with joins.
     *
     * @return Zend_Db_Select
     */
    protected function _getBaseSelect()
    {
        $baseSelect = new Zend_Db_Select(self::getDefaultAdapter());
        $fields     = $this->getMapper()->getFields();

        if(empty($fields) && !empty($this->_logger))
        {
            $this->_logger->log(
                "\r\n---------------------------   [" . get_class($this) . '::_getBaseSelect] ----------------------' .
                "\r\n  FIELDS NOT FOUND !!!!!!
                \r\n---------------------------------------------------------------------------------------------\r\n",
                Zend_Log::DEBUG
            );
        }
        $baseSelect
            ->from($this->_name, $fields);

        $joinedEntities = self::_getJoins($this->getMapper());

        foreach($joinedEntities as $join)
        {
            if($join->type == self::JOIN_INNER)
            {
                $baseSelect->joinInner(
                    $join->table, '(' . $join->rule . ')', $join->fields
                );
            }
            else
            {
                $baseSelect->joinLeft(
                    $join->table, '(' . $join->rule . ')', $join->fields
                );
            }
        }
        $this->_logQuery($baseSelect, '_getBaseSelect');
        return $baseSelect;
    }

    /**
     * Gets join conditions.
     *
     * @param Core_Entity_Mapper_Abstract $mapper     Mapper.
     * @param string                      $bindName   Bind Name.
     * @param integer                     $level      Level.
     * @param string                      $skipMapper Mapper to skip back loading.
     *
     * @return array
     */
    protected static function _getJoins(Core_Entity_Mapper_Abstract $mapper, $bindName = null, $level = 0, $skipMapper = null)
    {
        $out      = array();
        $bindPath = (empty($bindName) ? '' : $bindName . ':');
        foreach($mapper->getRelations() as $alias => $config)
        {
            if((isset($config['load']) && $config['load'] !== false) || $mapper->isWith($alias))
            {
                switch($config['type'])
                {
                    case Core_Entity_Mapper_Abstract::RELATION_CHILD:
                        if($level < self::$_joinNestingLevel && ($skipMapper == null || !($skipMapper[0] == $config['mapper'] && $skipMapper[1] == $config['type'])))
                        {
                            $related                 = $config['mapper'];
                            $related                 = $related::getInstance();
                            $out[$bindPath . $alias] = self::_getChildJoin($mapper,
                                    $config, $related, $bindPath, $alias,
                                    $bindName);
                            $out += self::_getJoins($related,
                                    $bindPath . $alias, $level + 1,
                                    array(get_class($mapper), Core_Entity_Mapper_Abstract::RELATION_PARENT));
                        }
                        break;

                    case Core_Entity_Mapper_Abstract::RELATION_PARENT:
                        if($level < self::$_joinNestingLevel && ($skipMapper == null || !($skipMapper[0] == $config['mapper'] && $skipMapper[1] == $config['type'])))
                        {
                            $related                 = $config['mapper'];
                            $related                 = $related::getInstance();
                            $out[$bindPath . $alias] = self::_getParentJoin($mapper,
                                    $config, $related, $bindPath, $alias,
                                    $bindName);
                            $out += self::_getJoins($related,
                                    $bindPath . $alias, $level + 1,
                                    array(get_class($mapper), Core_Entity_Mapper_Abstract::RELATION_CHILD));
                        }
                        break;

                    default:
                        /**
                         * @todo Nothing here.
                         */
                        break;
                }
            }
        }
        return $out;
    }

    /**
     *  Gets a parent relation joins.
     *
     * @param Core_Entity_Mapper_Abstract $mapper   A mapper.
     * @param mixed                       $config   Relation Config.
     * @param Core_Entity_Mapper_Abstract $related  Related Mapper.
     * @param string                      $bindPath Bind Path.
     * @param string                      $alias    An Alias.
     * @param string                      $bindName A bind Name.
     *
     * @return stdClass
     */
    private static function _getParentJoin(Core_Entity_Mapper_Abstract $mapper, $config, Core_Entity_Mapper_Abstract $related, $bindPath, $alias, $bindName)
    {
        $joinItem                     = new stdClass();
        $joinItem
            ->table = array(
            $bindPath . $alias => $related->getStorage()->info(self::NAME)
        );

        $joinItem
            ->type = ((strtolower($config['load']) != self::JOIN_INNER) ? self::JOIN_LEFT : self::JOIN_INNER);
        $joinItem->rule              = '`' . (empty($bindName) ? $mapper->getStorage()->info(self::NAME) : $bindName) . '`.`' . $mapper->map($config['fKey']) . '` = `' . $bindPath . $alias . '`.`' . $related->map($config['key']) . '`';

        if(isset($config['filter']) && is_array($config['filter']))
        {
            $filter = new Core_Entity_Storage_DbSelect_Filter(
                array(
                Core_Entity_Storage_DbSelect_Filter::MAPPER      => $related,
                Core_Entity_Storage_DbSelect_Filter::TABLE_ALIAS => $bindPath . $alias,
                Core_Entity_Storage_DbSelect_Filter::FILTER      => $config['filter']
                )
            );
            $joinItem->rule .= ' AND ' . $filter->assemble();
        }
        foreach($related->getFields() as $relatedAlias => $realtedField)
        {
            $joinItem->fields[$bindPath . $alias . ':' . $relatedAlias] = $bindPath . $alias . '.' . $realtedField;
        }
        return $joinItem;
    }

    /**
     *  Gets a child relation joins.
     *
     * @param Core_Entity_Mapper_Abstract $mapper   A mapper.
     * @param mixed                       $config   Relation Config.
     * @param Core_Entity_Mapper_Abstract $related  Related Mapper.
     * @param string                      $bindPath Bind Path.
     * @param string                      $alias    An Alias.
     * @param string                      $bindName A bind Name.
     *
     * @return stdClass
     */
    private static function _getChildJoin(Core_Entity_Mapper_Abstract $mapper, $config, Core_Entity_Mapper_Abstract $related, $bindPath, $alias, $bindName)
    {
        $joinItem                     = new stdClass();
        $joinItem
            ->table = array(
            $bindPath . $alias => $related->getStorage()->info(self::NAME)
        );

        $joinItem
            ->type = ((strtolower($config['load']) != self::JOIN_INNER) ? self::JOIN_LEFT : self::JOIN_INNER);

        $joinItem
            ->rule = '`' . (empty($bindName) ? $mapper->getStorage()->info(self::NAME) : $bindName) . '`.`' . $mapper->map($config['key']) . '` = `' . $bindPath . $alias . '`.`' . $related->map($config['fKey']) . '`';

        if(isset($config['filter']) && is_array($config['filter']))
        {
            $filter = new Core_Entity_Storage_DbSelect_Filter(
                array(
                Core_Entity_Storage_DbSelect_Filter::MAPPER      => $related,
                Core_Entity_Storage_DbSelect_Filter::TABLE_ALIAS => $bindPath . $alias,
                Core_Entity_Storage_DbSelect_Filter::FILTER      => $config['filter']
                )
            );
            $joinItem->rule .= ' AND ' . $filter->assemble();
        }
        foreach($related->getFields() as $relatedAlias => $realtedField)
        {
            $joinItem->fields[$bindPath . $alias . ':' . $relatedAlias] = $bindPath . $alias . '.' . $realtedField;
        }
        return $joinItem;
    }

    /**
     *  Saving data.
     *
     * @param Core_Entity_Model_Abstract $model A model.to save.
     * @param array                      $data  A data to push to storage.
     *
     * @return boolean
     * @throws Core_Entity_Exception On Error.
     */
    public function save(Core_Entity_Model_Abstract $model, array $data)
    {
        $transactionStarted = false;
        if(self::$_manualTransactions && !self::$_manualTransactionStarted)
        {
            self::getDefaultAdapter()->beginTransaction();
            self::$_manualTransactionStarted = $transactionStarted = true;
        }

        $keyVal   = $model->getKey();
        $isLoaded = $model->isLoaded();

        if(!$this->info(self::SEQUENCE) && empty($keyVal))
        {
            throw new Core_Entity_Exception('For non sequenced models primary key value is required');
        }
        if(!$isLoaded && !empty($keyVal))
        {
            $isLoaded = ($this->count(array($this->getMapper()->getKeyField() => $keyVal)) == 1) ? true : false;
        }
        if(!$isLoaded)
        {
            $result = self::getDefaultAdapter()
                ->insert(
                $this->info(self::NAME), $data
            );
        }
        else
        {
            $result = self::getDefaultAdapter()
                ->update(
                $this->info(self::NAME), $data,
                array($this->getMapper()->map($this->getMapper()->getKeyField()) . ' = ?' => $keyVal)
            );
        }

        if($result && !$isLoaded && $this->info(self::SEQUENCE))
        {
            $result = self::getDefaultAdapter()->lastInsertId();
        }
        elseif($result)
        {
            $result = $keyVal;
        }
        if(self::$_manualTransactions && self::$_manualTransactionStarted && $transactionStarted)
        {
            self::getDefaultAdapter()->commit();
        }
        return $result;
    }

    /**
     *  Removing model data.
     *
     * @param mixed $entityKeyVal Primary value.
     *
     * @return mixed
     */
    public function delete($entityKeyVal)
    {
        return self::getDefaultAdapter()
                ->delete(
                    $this->info(self::NAME),
                    array($this->getMapper()->map($this->getMapper()->getKeyField()) . ' = ?' => $entityKeyVal)
        );
    }

    /**
     * Get an instance of Request object that allows more flexibility.
     *
     * @return Core_Entity_Storage_DbSelect_Request
     */
    public function getRequest()
    {
        return new Core_Entity_Storage_DbSelect_Request($this->_getBaseSelect(),
            $this->getMapper());
    }

    /**
     * Get base query for grid.
     *
     * @return Zend_Db_Select
     */
    public function getBaseQuery()
    {
        return $this->_getBaseSelect();
    }

}

