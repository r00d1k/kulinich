<?php
class Core_Grid_Modifier_Date extends Core_Grid_Modifier_Abstract
{
    public function _process($value)
    {
        if(!($value instanceof Zend_Date))
        {
            $value = new Zend_Date(strtotime($value));
        }
        return $value->get($this->_options[0]);
    }
}