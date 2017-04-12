<?php
class Core_Grid_Source_DbSelect extends Core_Grid_Source_Abstract
{
    /** @var Zend_Db_Select null */
    protected $_source = null;
    protected $_count = null;
    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     *       The return value is cast to an integer.
     */
    public function count()
    {
        if($this->_count == null)
        {
            $select = clone $this->_source;
            $group = $select->getPart(Zend_Db_Select::GROUP);
            if(!empty($group))
            {
                $select
                    ->reset(Zend_Db_Select::COLUMNS)
                    ->reset(Zend_Db_Select::GROUP)
                    ->columns(
                    array(
                         'count(DISTINCT ' . implode(', ', $group) . ')'
                    )
                );
            }
            else
            {
                $select
                    ->reset(Zend_Db_Select::COLUMNS)
                    ->columns(
                    array(
                         'count(*)'
                    )
                );
            }
            $this->_count = Zend_Db_Table::getDefaultAdapter()->fetchOne($select);
        }

        return $this->_count;
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
        $select = clone $this->_source;
        $select->limitPage($page, $this->_recordsPerPage);
        //Zend_Debug::dump($select->assemble());exit();
        return Zend_Db_Table::getDefaultAdapter()->fetchAll($select);
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
        $this->_source->where($this->_prepareCondition($alias, $value, $condition));
    }

    public function orWhere($alias, $value = null, $condition = null)
    {
        $this->_source->orWhere($this->_prepareCondition($alias, $value, $condition));
    }

    protected function _prepareCondition($alias, $value = null, $condition = null)
    {
        if(!is_array($alias))
        {
            $alias = array(
                Core_Entity_Mapper_Abstract::FILTER_FIELD => $alias,
                Core_Entity_Mapper_Abstract::FILTER_VALUE => $value,
                Core_Entity_Mapper_Abstract::FILTER_COND => $condition
            );
        }
        if(array_key_exists(Core_Entity_Mapper_Abstract::FILTER_COND, $alias))
        {
            $alias = array($alias);
        }
        $condition = '';
        $prevJoiner = null;
        foreach($alias as $key => $filterPart)
        {
            if(!isset($filterPart[Core_Entity_Mapper_Abstract::FILTER_FIELD]))
            {
                $filterPart[Core_Entity_Mapper_Abstract::FILTER_FIELD] = $key;
            }
            if($prevJoiner != null)
            {
                $condition .= ' '.$prevJoiner . ' ';
            }
            $prevJoiner = !empty($filterPart[Core_Entity_Mapper_Abstract::FILTER_LOGIC]) ? $filterPart[Core_Entity_Mapper_Abstract::FILTER_LOGIC] : 'OR';
            $condition .= $filterPart[Core_Entity_Mapper_Abstract::FILTER_FIELD ] . ' ' .
                $filterPart[Core_Entity_Mapper_Abstract::FILTER_COND] . ' \'' .
                $filterPart[Core_Entity_Mapper_Abstract::FILTER_VALUE] . '\'';
        }
        return $condition;
    }
}