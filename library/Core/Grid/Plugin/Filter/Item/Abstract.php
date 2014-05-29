<?php
/**
 * @author P.Matvienko
 * @file Abstract.php
 * @created 04.03.13
 */
abstract class Core_Grid_Plugin_Filter_Item_Abstract
{
    const TYPE_SEARCH = 'search';
    const TYPE_TEXT = 'text';
    const TYPE_NUMERIC = 'numeric';
    const TYPE_DATE = 'date';
    const TYPE_SELECT = 'combo';


    const SETTING_TYPE = 'type';
    const SETTING_JOINER = 'joiner';
    const SETTING_FIELD = 'field';
    const SETTING_OPTIONS = 'options';
    const SETTING_LABEL = 'label';

    const JOINER_AND = 'AND';
    const JOINER_OR = 'OR';

    const NS = 'Core_Grid_Plugin_Filter_Item_';

    protected $_name = null;
    protected $_id = '';
    protected $_field = null;
    protected $_label = null;

    public function __construct($field)
    {
        $this->_field = $field;
    }

    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }

    public function getLabel()
    {
        if($this->_label != null)
        {
            return $this->_label;
        }
        if($this->_name != null)
        {
            return $this->_name;
        }
        return '';
    }

    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    abstract public function getValue();

    public static function factory($filter)
    {
        if(empty($filter[self::SETTING_TYPE]))
        {
            throw new Zend_Exception('Filter type is not set');
        }
        if(empty($filter[self::SETTING_FIELD]))
        {
            throw new Zend_Exception('Field is not set.');
        }
        $className = self::NS . ucfirst($filter[self::SETTING_TYPE]);
        switch ($filter[self::SETTING_TYPE])
        {
            case self::TYPE_SEARCH:
                return new $className(
                    $filter[self::SETTING_FIELD],
                    !empty($filter[self::SETTING_JOINER]) ? $filter[self::SETTING_JOINER] : self::JOINER_OR
                );
                break;
            case self::TYPE_TEXT:
                $item = new $className(
                    $filter[self::SETTING_FIELD]
                );
                if(!empty($filter[self::SETTING_LABEL]))
                {
                    $item->setLabel($filter[self::SETTING_LABEL]);
                }
                return $item;
                break;
            default:
                throw new Zend_Exception('!!! Filter type "' . $filter[self::SETTING_TYPE] . '" is not implemented !!!');
                break;
        }
    }

    protected static $_conditions = array();

    public static function getConditions()
    {
        return self::$_conditions;
    }

    public function assembleCondition()
    {

    }
}