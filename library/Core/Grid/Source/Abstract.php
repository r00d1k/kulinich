<?php
abstract class Core_Grid_Source_Abstract implements Countable
{
    protected $_source = null;

    /** @var integer */
    protected $_recordsPerPage = 10;
    /** @var array */
    protected $_order = array();
    /** @var array */
    protected $_limit = array(null, null);

    public function __construct($source)
    {
        $this->_source = $source;
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
     * Counting pages based on limitPerPage value and setted filter.
     *
     * @return integer
     */
    public function countPages()
    {
        return ceil(count($this)/$this->_recordsPerPage);
    }

    /**
     * @param $source
     *
     * @return Core_Grid_Source_Abstract
     */
    public static function factory($source)
    {
        if($source instanceof Zend_Db_Select)
        {
            return new Core_Grid_Source_DbSelect($source);
        }
    }

    abstract public function getPage($page = 1);
}