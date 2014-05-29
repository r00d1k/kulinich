<?php
/**
 * @author P.Matvienko
 * @file Abstract.php
 * @created 03.03.13
 */
abstract class Core_Grid_Plugin_Filter_Abstract
{
    const SETTING_TYPE = 'type';
    const JOINER_AND = 'AND';
    const JOINER_OR = 'OR';

    protected $_filters = array();
    protected $_filtersMainJoiner = self::JOINER_AND;
    protected $_id = null;


    public function __construct($options)
    {
        foreach($options as $k=>$v)
        {
            $call = 'set'.ucfirst($k);
            if(method_exists($this, $call))
            {
                $this->$call($v);
            }
        }
    }

    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    public function setFiltersJoiner($joiner)
    {
        $this->_filtersMainJoiner = $joiner;
        return $this;
    }

    public function setFilters($filters)
    {
        $this->_filters = array();
        foreach($filters as $name => $filter)
        {
            $this->addFilter($name, Core_Grid_Plugin_Filter_Item_Abstract::factory($filter));
        }
        return $this;
    }

    public function addFilter($name, Core_Grid_Plugin_Filter_Item_Abstract $filter)
    {
        if($filter instanceof Core_Grid_Plugin_Filter_Item_Search)
        {
            $this->_filters['Grid_Filter_Search'] = $filter;
        }
        else
        {
            $this->_filters[$name] = $filter->setName($name);
        }
        return $this;
    }
    public function getFiltersSet()
    {
        foreach($this->_filters as $filter)
        {
            $filter->setId($this->_id);
        }
        return $this->_filters;
    }

    public function applyPlugin($dataSource)
    {
        foreach ($this->getFiltersSet()as $filter)
        {
            $condition = $filter->assembleCondition();
            if(!empty($condition))
            {
                if($this->_filtersMainJoiner == self::JOINER_AND)
                {
                    $dataSource->where($condition);
                }
                else
                {
                    $dataSource->orWhere($condition);
                }
            }
        }
        return $dataSource;
    }

    public function render()
    {
        return Core_Grid_Html_Abstract::getView()->partial(
            'xhtmlGridFilter.phtml',
            array(
                 'filtersSet' => $this->getFiltersSet(),
                 'filter' => $this,
                 'id' => !empty($this->_id) ? $this->_id.'_' : '',
                 'lngSection' => !empty($this->_id) ? $this->_id.':' : ''
            )
        );
    }
}