<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Converter_Ip
 * @subpackage Converter
 * @use Core_Entity_Converter_Interface, Core_Entity_Converter_Abstract
 *
 * Ip converter. Converting ip to long on insert/update and back on select.
 */
class Core_Entity_Converter_Ip extends Core_Entity_Converter_Abstract
{
    /**
     * Encoder.
     *
     * @param mixed $value Value to encode.
     *
     * @return string
     */
    public function encode($value)
    {
        if(is_int($value))
        {
            return $value;
        }
        return ip2long($value);
    }

    /**
     * Decoder.
     *
     * @param mixed $value Value to decode.
     *
     * @return string
     */
    public function decode($value)
    {
        return long2ip($value);
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
        if(is_int($value) || is_numeric($value))
        {
            return $this->decode((int)$value);
        }
        return $value;
    }
}