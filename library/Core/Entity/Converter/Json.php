<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Converter_Json
 * @subpackage Converter
 * @use Core_Entity_Converter_Interface, Core_Entity_Converter_Abstract
 *
 * Json converter. This converter allows you store an arrays, and not only, in database as json object.
 */
class Core_Entity_Converter_Json extends Core_Entity_Converter_Abstract
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
        if(empty($value))
        {
            return null;
        }
        try
        {
            $value = Zend_Json::encode($value);
        }
        catch(Exception $e)
        {
            return null;
        }
        return $value;
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
        if(empty($value))
        {
            return null;
        }
        try
        {
            $value = Zend_Json::decode($value);
        }
        catch(Exception $e)
        {
            return null;
        }
        return $value;
    }
}