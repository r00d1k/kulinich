<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Converter_Datetime
 * @subpackage Converter
 * @use Core_Entity_Converter_Interface, Core_Entity_Converter_Abstract
 *
 * Date converter. Used for date field types processing.
 * Converting date to mysql format on insert/update.
 * Converting date to Zend_Date Instance on select.
 */
class Core_Entity_Converter_Datetime extends Core_Entity_Converter_Abstract
{
    protected $_dbMask = 'YYYY-MM-dd HH:mm:ss';
    /**
     * Encoder.
     *
     * @param mixed $value Value to encode.
     *
     * @return string
     */
    public function encode($value)
    {
        return $value->toString($this->_dbMask);
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
        return new Core_Entity_Date($value, $this->_dbMask);
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
        if($value instanceof Zend_Date)
        {
            return $value;
        }
        if($value != '')
        {
            return new Core_Entity_Date($value);
        }
    }

}