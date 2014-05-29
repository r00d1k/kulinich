<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Request_Abstract
 * @subpackage Request
 * @use Countable
 *
 * Abstract data request. This class created to add ability to build chained conditions.
 *
 * !!! Tested ONLY WITH database !!!
 * May not work with other data storages, like REST or SOAP.
 */
abstract class Core_Entity_Request_Abstract implements Countable
{
    /** @var array */
    protected $_filter = array();
    /** @var array */
    protected $_mapperFilter = null;
    /** @var Core_Entity_Storage_Interface $_storage */
    protected $_storage = null;
    /** @var integer */
    protected $_recordsPerPage = 10;
    /** @var array */
    protected $_order = array();
    /** @var array */
    protected $_limit = array(null, null);

    /**
     *  Constructor.
     *
     * @param mixed $initial Initial data.
     */
    public function __construct($initial = array())
    {
        if(is_array($initial))
        {
            foreach($initial as $k => $v)
            {
                if(method_exists($this, 'set'.  ucfirst($k)) && !empty($v))
                {
                    $this->{'set'.  ucfirst($k)}($v);
                }
            }
            if(isset($initial['mapperFilter']))
            {
                $this->_mapperFilter = $initial['mapperFilter'];
            }
        }
    }

    /**
     *  Setting Where.
     *
     * @param string               $alias     Field.
     * @param string|integer|array $value     Value.
     * @param string               $condition Eq Type( =, <>).
     *
     * @return Core_Entity_Request_Abstract
     * @throws Core_Entity_Exception On Error.
     */
    public function where($alias, $value = null, $condition = null)
    {
        return $this->_where($alias, $value, $condition, 'AND');
    }

    /**
     *  Sets Or Where.
     *
     * @param string               $alias     Field.
     * @param string|integer|array $value     Value.
     * @param string               $condition Eq Type( =, <>).
     *
     * @return Core_Entity_Storage_DbSelect_Rowset_Abstract
     */
    public function orWhere($alias, $value = null, $condition = null)
    {
        return $this->_where($alias, $value, $condition, 'OR');
    }

    /**
     *  Sets Where.
     *
     * @param string               $alias     Field.
     * @param string|integer|array $value     Value.
     * @param string               $condition Eq Type( =, <>).
     * @param string               $logics    OR,AND.
     *
     * @return Core_Entity_Request_Abstract
     */
    protected function _where($alias, $value = null, $condition = null, $logics = 'AND')
    {
        if(empty($alias))
        {
            return $this;
        }
        if(is_array($alias))
        {
            $this->_filter[] = array
            (
                Core_Entity_Mapper_Abstract::FILTER_LOGIC => $logics,
                Core_Entity_Mapper_Abstract::FILTER_VALUE  => $alias
            );
        }
        else
        {
            if(empty($condition))
            {
                $condition = '=';
            }
            $this->_filter[] = array(
                Core_Entity_Mapper_Abstract::FILTER_LOGIC => $logics,
                Core_Entity_Mapper_Abstract::FILTER_VALUE  => $value,
                Core_Entity_Mapper_Abstract::FILTER_COND => $condition,
                Core_Entity_Mapper_Abstract::FILTER_FIELD => $alias
            );
        }
        return $this;
    }
    /**
     * Setting order.
     *
     * @param string $orderField     Field to order by.
     * @param string $orderDirection Order Direction.
     *
     * @return Core_Entity_Request_Abstract
     */
    public function order($orderField, $orderDirection = '')
    {
        $this->_order[$orderField] = $orderField . ' ' . $orderDirection;
        return $this;
    }

    /**
     * Sets a per page limit.
     *
     * @param integer $count Rows count.
     *
     * @return Core_Entity_Request_Abstract
     */
    public function limitPerPage($count)
    {
        if($count < 1)
        {
            $count = 10;
        }
        $this->_recordsPerPage = $count;
        return $this;
    }

    /**
     * Setting limit.
     *
     * @param integer $count  Records count.
     * @param integer $offset Offset.
     *
     * @return Core_Entity_Request_Abstract
     */
    public function limit($count, $offset = 0)
    {
        $this->_limit = array($count, $offset);
        return $this;
    }

    /**
     * Storage Setter.
     *
     * @param Core_Entity_Storage_Interface $storage Storage instance.
     *
     * @return Core_Entity_Request_Abstract
     */
    public function setStorage(Core_Entity_Storage_Interface $storage)
    {
        $this->_storage = $storage;
        return $this;
    }

    /**
     * Storage Getter.
     *
     * @return Core_Entity_Storage_Interface|null
     */
    public function getStorage()
    {
        return $this->_storage;
    }

    /**
     * Gets a records count matching to setted filter.
     *
     * @return integer
     */
    public function count()
    {
        return $this->getStorage()->count($this->getFilter());
    }

    /**
     * Counting pages based on limitPerPage value and setted filter.
     *
     * @return integer
     */
    public function countPages()
    {
        return ceil(count($this)/$this->_recordsPerPage);
    }

    /**
     * Getting a first row matching to setted filter.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function getRow()
    {
        return $this->getStorage()->fetchRow($this->getFilter());
    }

    /**
     * Gets all rows matching to setted filter.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function getAll()
    {
        return $this->getStorage()->fetchAll($this->getFilter(), $this->_order, $this->_limit[0], $this->_limit[1]);
    }

    /**
     * Gets all rows matching to setted filter.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function getAllRaw()
    {
        return $this->getStorage()->fetchAllAsArray($this->getFilter(), $this->_order, $this->_limit[0], $this->_limit[1]);
    }

    /**
     * Gets all rows matching to setted filter. Ordered by setted order rules and cutted by pagination limit.
     *
     * @param integer $page A page number to get.
     *
     * @return Core_Entity_Rowset_Abstract
     */
    public function getPage($page = 1)
    {
        return $this->getStorage()->fetchAll($this->getFilter(), $this->_order, $this->_recordsPerPage, (($page-1)*$this->_recordsPerPage));
    }

    /**
     * Gets all rows matching to setted filter. Ordered by setted order rules and cutted by pagination limit.
     *
     * @param integer $page A page number to get.
     *
     * @return Core_Entity_Rowset_Abstract
     */
    public function getPageRaw($page = 1)
    {
        return $this->getStorage()->fetchAllAsArray($this->getFilter(), $this->_order, $this->_recordsPerPage, (($page-1)*$this->_recordsPerPage));
    }

    /**
     * Gets a full filter.
     *
     * @return array
     */
    public function getFilter()
    {
        $out = array();
        if(empty($this->_mapperFilter) && !empty($this->_filter))
        {
            $out = $this->_filter;
        }
        elseif(!empty($this->_mapperFilter) && empty($this->_filter))
        {
            $out = $this->_mapperFilter;
        }
        elseif(!empty($this->_mapperFilter) && !empty($this->_filter))
        {
            $out = array(
                array(
                    Core_Entity_Mapper_Abstract::FILTER_LOGIC => 'AND',
                    Core_Entity_Mapper_Abstract::FILTER_VALUE  => $this->_mapperFilter
                ),
                array(
                    Core_Entity_Mapper_Abstract::FILTER_LOGIC => 'AND',
                    Core_Entity_Mapper_Abstract::FILTER_VALUE  => $this->_filter
                )
            );
        }
        return $out;
    }

    public function with($relations)
    {
        $this->getStorage()->getMapper()->with($relations);
        return $this;
    }
}