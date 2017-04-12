<?php
require_once 'Zend/View/Helper/Abstract.php';
/**
 * Core_View_Helper_Cycle.
 *
 * User: Alexander
 * Date: 12.10.11
 * Time: 15:32
 */
class Core_View_Helper_Cycle extends Zend_View_Helper_Abstract
{
    /**
     * @var integer
     */
    private static $_cycle = -1;


    /**
     * Iterates through the array.
     *
     * @param array $data Array data (example ('odd', 'even')).
     *
     * @return string
     */
    public function cycle(array $data)
    {
        if(count($data) <= ++self::$_cycle)
        {
            self::$_cycle = 0;
        }
        return $data[self::$_cycle];
    }
}