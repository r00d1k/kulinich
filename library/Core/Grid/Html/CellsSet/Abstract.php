<?php
abstract class Core_Grid_Html_CellsSet_Abstract
{
    protected $_isOrderEnabled = false;
    protected $_orderField = null;
    protected $_label = null;
    protected $_title = null;
    protected $_content = null;
    protected $_gridId = null;
    protected $_translateSection = null;
    protected static $_view = null;
    protected $_htmlAttributes = array();

    public function __construct($config)
    {
        foreach($config as $parameter => $value)
        {
            $setter = 'set'.ucfirst(strtolower($parameter));
            if(method_exists($this, $setter))
            {
                $this->$setter($value);
            }
            else
            {
//                /var_dump($setter);
            }
        }
    }

    public function isHidden()
    {
        return false;
    }

    public function htmlAttributes($attributes)
    {
        $this->_htmlAttributes = $attributes;
    }

    public function setTranslationSection($section)
    {
        $this->_translateSection = $section;
        return $this;
    }

    public function setGridId($id)
    {
        $this->_gridId = $id;
        return $this;
    }

    public function setLabel($value)
    {
        $this->_label = $value;
        return $this;
    }
    public function setTitle($value)
    {
        $this->_title = $value;
        return $this;
    }
    public function setContent($value)
    {
        $this->_content = $value;
        return $this;
    }

    public function setOrder($field = null)
    {
        if($field != null)
        {
            $this->_isOrderEnabled = true;
            $this->_orderField = $field;
        }
        else
        {
            $this->_isOrderEnabled = false;
            $this->_orderField = null;
        }
        return $this;
    }

    protected function _translate($text)
    {
        if(null != $this->_translateSection && !stristr($text, ':'))
        {
            $text = $this->_translateSection . ':' . $text;
        }
        return $this->_getView()->translate($text);
    }

    public function getLabel()
    {
        return $this->_label;
    }

    public function getTitle()
    {
        return $this->_title;
    }
    public function isOrderColumn()
    {
        return $this->_isOrderEnabled;
    }

    public function isOrderedBy()
    {
        $selfField = $this->getOrderField();
        if(empty($selfField))
        {
            return false;
        }
        $orderField = Zend_Controller_Front::getInstance()
            ->getRequest()
            ->getParam('order-field-'.$this->_gridId, null);
        if($orderField == $this->getOrderField())
        {
            return true;
        }
        return false;
    }

    public function getOrderField()
    {
        return $this->_orderField;
    }
    public function getOrderDirection()
    {
        $orderField = Zend_Controller_Front::getInstance()
            ->getRequest()
            ->getParam('order-field-'.$this->_gridId, null);
        if($orderField != $this->getOrderField())
        {
            return null;
        }
        else
        {
            return Zend_Controller_Front::getInstance()
                ->getRequest()
                ->getParam('order-direction-'.$this->_gridId, null);
        }
    }

    protected function _compileContent($values, $string)
    {
        $variables = array();
        preg_match_all('/\{\{([^}]+)\}\}/', $string, $variables);

        foreach($variables[1] as $index => $variable)
        {
            $variable = explode(':|', $variable);
            $value = '';
            if($values instanceof Core_Entity_Model_Abstract)
            {
                $variableToGet = $variable[0];
                $value = $values->$variableToGet;
            }
            elseif(is_array($values))
            {
                $value = $values[$variable[0]];
            }
            if(count($variable > 1))
            {
                unset($variable[0]);
                $value = Core_Grid_Modifier_Abstract::modify($value, $variable);
            }

            $string = str_replace($variables[0][$index], $value, $string);
        }
        return $string;
    }
    public function renderCell($row)
    {
        return '<td>' . $this->renderCellContent($row) . '</td>';
    }
}