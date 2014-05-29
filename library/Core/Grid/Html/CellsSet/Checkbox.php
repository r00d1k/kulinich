<?php
class Core_Grid_Html_CellsSet_Checkbox extends Core_Grid_Html_CellsSet_Abstract
{
    protected $_valueChecked = 'yes';
    protected $_valueUnchecked = 'no';
    protected $_disabled = false;

    public function setValueChecked($val)
    {
        $this->_valueChecked = $val;
        return $this;
    }

    public function setValueUnchecked($val)
    {
        $this->_valueUnchecked = $val;
        return $this;
    }

    public function setDisabled($val)
    {
        $this->_disabled = $val;
        return $this;
    }

    public function renderCellContent($values)
    {
        if($values instanceof Core_Entity_Model_Abstract)
        {
            $value = $values->{$this->_content};
        }
        elseif(is_array($values))
        {
            $value = !empty($values[$this->_content]) ? $values[$this->_content] : null;
        }
        $checked = '';
        $disabled = '';
        if($this->_valueChecked === $value || ($this->_valueChecked === true && strtolower($value) == 'yes'))
        {
            $checked = ' checked="checked"';
        }
        if($this->_disabled===true || $this->_disabled == 'yes' || $this->_disabled = 'disabled')
        {
            $disabled = ' disabled="disabled"';
        }


        return '
        <input type="checkbox"'.$checked.$disabled.' />
        ';
    }

}