<?php
abstract class Core_Grid_Modifier_Abstract
{
    protected $_options = array();

    public function __construct($options)
    {
        $this->_options = $options;
    }

    public static function modify($value, $options)
    {
        foreach($options as $set)
        {
            $set = explode('%', $set);
            $modifier = array_splice($set,0,1);
            $modifier = 'Core_Grid_Modifier_' . ucfirst(strtolower($modifier[0]));
            $modifier = new $modifier($set);
            $value = $modifier->_process($value);
        }
        return $value;
    }

    abstract protected function _process($value);
}