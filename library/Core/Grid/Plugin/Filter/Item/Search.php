<?php
/**
 * @author P.Matvienko
 * @file Search.php
 * @created 04.03.13
 */
class Core_Grid_Plugin_Filter_Item_Search extends Core_Grid_Plugin_Filter_Item_Abstract
{
    protected $_name = 'Grid_Filter_Search';
    protected $_fields = null;
    protected $_joiner = null;

    public function __construct($fields, $joiner = self::JOINER_OR)
    {
        $this->_fields = $fields;
        $this->_joiner = $joiner;
    }

    public function setName($name)
    {
        return $this;
    }

    public function getValue()
    {
        $paramName = $this->getId();
        $paramName = (!empty($paramName) ? $paramName . '_' : '') . $this->getName();

        if(!empty($_COOKIE[$paramName]))
        {
            return $_COOKIE[$paramName];
        }
        return null;
    }

    public function assembleCondition()
    {
        $value = $this->getValue();
        if(empty($value))
        {
            return null;
        }
        $conditions = array();
        foreach($this->_fields as $field)
        {
            $conditions[$field] = array(
                Core_Entity_Mapper_Abstract::FILTER_COND => 'LIKE',
                Core_Entity_Mapper_Abstract::FILTER_VALUE => '%'.$value.'%',
                Core_Entity_Mapper_Abstract::FILTER_LOGIC => $this->_joiner
            );
        }
        return $conditions;
    }
}