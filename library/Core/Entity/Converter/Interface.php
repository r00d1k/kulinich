<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Converter_Interface
 * @subpackage Converter
 *
 * Converter interface.
 * You can create your own converters by implementing this interface or extending Core_Entity_Converter_Abstract class.
 */
interface Core_Entity_Converter_Interface
{
    /**
     * Encode value on save.
     *
     * @param mixed $value Value to encode.
     *
     * @return mixed
     */
    public function encode($value);

    /**
     * Decode value on load.
     *
     * @param mixed $value Value to decode.
     *
     * @return mixed
     */
    public function decode($value);

    /**
     * Check value on set.
     *
     * @param mixed $value Value to check.
     *
     * @return mixed
     */
    public function check($value);
}