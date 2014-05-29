<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Converter_Abstract
 * @subpackage Converter
 * @use Core_Entity_Converter_Interface
 *
 * Abstract converter.
 *
 * This class created to provide base functionality needed for converters in current system.
 */
abstract class Core_Entity_Converter_Abstract implements Core_Entity_Converter_Interface
{
    protected static $_instances = array();
    /**
     *  Gets a Converter instance.
     *
     * @return Core_Entity_Converter_Abstract
     */
    public static function getInstance()
    {
        $converterName = get_called_class();
        if(!isset(self::$_instances[$converterName]))
        {
            $class = new $converterName();
            self::$_instances[$converterName] = &$class;
        }
        return self::$_instances[$converterName];
    }

    /**
     * Check value on set.
     *
     * @param mixed $value Value to check.
     *
     * @return mixed
     */
    public function check($value)
    {
        return $value;
    }
}