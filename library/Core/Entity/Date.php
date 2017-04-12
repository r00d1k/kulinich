<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Date
 * @subpackage Converter
 * @use Zend_Date
 *
 * This date class developed for Core_Entity.
 * When date is not set it returns an empty string instead of wrong date.
 */
class Core_Entity_Date extends Zend_Date
{
    private $_isTimeNull = false;
    public function __construct($date = null, $part = null, $locale = null)
    {
        if($date == null)
        {
            $this->_isTimeNull= true;
        }
        parent::__construct($date, $part, $locale);
    }

    public function get($part = null, $locale = null)
    {
        if($this->_isTimeNull)
        {
            return '';
        }
        return parent::get($part, $locale);
    }
}