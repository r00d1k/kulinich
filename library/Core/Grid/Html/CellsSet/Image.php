<?php
class Core_Grid_Html_CellsSet_Image extends Core_Grid_Html_CellsSet_Abstract
{
    protected $_style="";

    public function setStyle($attribs)
    {
        $this->_style = $attribs;
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
        if(!empty($value))
        {
            return '<img style="'.$this->_style.'" src="'.$value.'" />';
        }
        else
        {
            return '&nbsp;';
        }
    }

}