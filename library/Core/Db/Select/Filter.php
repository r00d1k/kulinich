<?php
/**
 * @package Core_Db
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Db_Select_Filter
 *
 * This class developed to process complex query conditions.
 */
class Core_Db_Select_Filter
{
    const TABLE_ALIAS = 'tableName';

    const MAPPER = 'mapper';
    const FILTER = 'filter';

    protected $_mapper = null;
    protected $_items = array();

    protected $_tableName = '';

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
        }
    }

    /**
     * Mapper setter.
     *
     * @param Core_Entity_Mapper_Abstract $mapper Mapper Instance.
     *
     * @return Core_Entity_Request_Abstract
     */
    public function setMapper(Core_Entity_Mapper_Abstract $mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    /**
     * Returns a table name.
     *
     * @return string
     */
    public function getTableName()
    {
        if(empty($this->_tableName))
        {
            $this->_tableName = $this->getMapper()->getStorage()->info(Core_Entity_Storage_DbSelect_Abstract::NAME);
        }
        return $this->_tableName;
    }

    /**
     * Sets a table name.
     *
     * @param string $tableName A table Name.
     *
     * @return Core_Entity_Storage_DbSelect_Filter
     */
    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;
        return $this;
    }

    /**
     * Mapper Getter.
     *
     * @return Core_Entity_Mapper_Abstract|null
     */
    public function getMapper()
    {
        return $this->_mapper;
    }

    /**
     * Setting Filter Array.
     *
     * @param array $filter Filter Condition.
     *
     * @return Core_Entity_Storage_DbSelect_Filter
     */
    public function setFilter(array $filter)
    {
        foreach($filter as $alias => $filterData)
        {
            if(!is_numeric($alias))
            {
                if(!is_array($filterData))
                {
                    $filterData = array(
                        Core_Entity_Mapper_Abstract::FILTER_COND => '=',
                        Core_Entity_Mapper_Abstract::FILTER_VALUE => $filterData,
                        Core_Entity_Mapper_Abstract::FILTER_LOGIC => 'AND'
                    );
                }
                if(!array_key_exists(Core_Entity_Mapper_Abstract::FILTER_LOGIC, $filterData))
                {
                    $filterData[Core_Entity_Mapper_Abstract::FILTER_LOGIC] = 'AND';
                }

                if(empty($filterData[Core_Entity_Mapper_Abstract::FILTER_FIELD]))
                {
                    $filterData[Core_Entity_Mapper_Abstract::FILTER_FIELD] = $alias;
                }

                if(!array_key_exists(Core_Entity_Mapper_Abstract::FILTER_VALUE, $filterData) && array_key_exists(Core_Entity_Mapper_Abstract::FILTER_VAL, $filterData))
                {
                    $filterData[Core_Entity_Mapper_Abstract::FILTER_VALUE] = $filterData[Core_Entity_Mapper_Abstract::FILTER_VAL];
                }
                $this->_items[] = $filterData;
            }
            elseif(is_array($filterData) && !is_array($filterData[Core_Entity_Mapper_Abstract::FILTER_VALUE]))
            {
                $this->_items[] = $filterData;
            }
            elseif(is_array($filterData) && is_array($filterData[Core_Entity_Mapper_Abstract::FILTER_VALUE]))
            {
                $filterData[Core_Entity_Mapper_Abstract::FILTER_VALUE] = new self(
                    array(
                         Core_Entity_Model_Abstract::CONSTRUCT_MAPPER => $this->getMapper(),
                         'filter' => $filterData[Core_Entity_Mapper_Abstract::FILTER_VALUE]
                    )
                );
                $this->_items[] = $filterData;
            }
        }
        return $this;
    }

    /**
     * Assembling filter.
     *
     * @return string
     * @throws Core_Entity_Exception On Error.
     */
    public function assemble()
    {
        $select = new Zend_Db_Select(Core_Entity_Storage_DbSelect::getDefaultAdapter());////$this->getMapper()->getStorage()->getDefaultAdapter());
        foreach($this->_items as $item)
        {
            $addMethod = 'where';
            if(!empty($item[Core_Entity_Mapper_Abstract::FILTER_LOGIC]) && strtolower($item[Core_Entity_Mapper_Abstract::FILTER_LOGIC]) == 'or')
            {
                $addMethod = 'orWhere';
            }

            if(!array_key_exists(Core_Entity_Mapper_Abstract::FILTER_VALUE, $item))
            {
                ob_end_clean();
                ob_start();
                echo "<pre style=\"text-align: left;\">\r\nCurrent Item:\r\n";
                var_dump($item);
                echo "\r\nAll Items:\r\n";
                var_dump($this->_items);
                echo "</pre>";
                $dump = ob_get_clean();
                throw new Core_Entity_Exception('Incorrect Filter. ' . $dump);
            }

            if($item[Core_Entity_Mapper_Abstract::FILTER_VALUE] instanceof self)
            {
                $select->$addMethod($item[Core_Entity_Mapper_Abstract::FILTER_VALUE]->assemble());
            }
            elseif($item[Core_Entity_Mapper_Abstract::FILTER_VALUE] === null)
            {
                $fieldToFilter = $this->getMapper()->map($item[Core_Entity_Mapper_Abstract::FILTER_FIELD]);
                if(!strpos($fieldToFilter, '.'))
                {
                    $fieldToFilter = '`' . $this->getTableName() . '`.`' . $fieldToFilter . '`';
                }
                $select->$addMethod(
                    (($item[Core_Entity_Mapper_Abstract::FILTER_COND] == '=') ? 'ISNULL' : 'NOT ISNULL').
                    '(' . $fieldToFilter . ')'
                );
            }
            elseif(strtolower($item[Core_Entity_Mapper_Abstract::FILTER_COND]) == 'in' || strtolower($item[Core_Entity_Mapper_Abstract::FILTER_COND]) == 'not in')
            {
                if(empty($item[Core_Entity_Mapper_Abstract::FILTER_VALUE]))
                {
                    $select->$addMethod('0');
                }
                else
                {
                    $fieldToFilter = $this->getMapper()->map($item[Core_Entity_Mapper_Abstract::FILTER_FIELD]);
                    if(!strpos($fieldToFilter, '.'))
                    {
                        $fieldToFilter = '`' . $this->getTableName() . '`.`' . $fieldToFilter . '`';
                    }
                    $select->$addMethod($fieldToFilter . ' ' . strtoupper($item[Core_Entity_Mapper_Abstract::FILTER_COND]) . ' (?)', $item[Core_Entity_Mapper_Abstract::FILTER_VALUE]);
                }
            }
            else
            {
                $fieldToFilter = $this->getMapper()->map($item[Core_Entity_Mapper_Abstract::FILTER_FIELD]);
                if(!strpos($fieldToFilter, '.'))
                {
                    $fieldToFilter = '`' . $this->getTableName() . '`.`' . $fieldToFilter . '`';
                }
                $select->$addMethod($fieldToFilter . ' ' . $item[Core_Entity_Mapper_Abstract::FILTER_COND] . ' ?', $item[Core_Entity_Mapper_Abstract::FILTER_VALUE]);
            }
        }
        return implode(' ', $select->getPart(Zend_Db_Select::WHERE));
    }
    /**
     * To String Implementation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->assemble();
    }
}