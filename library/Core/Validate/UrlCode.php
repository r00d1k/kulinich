<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Alnum.php 23775 2011-03-01 17:25:24Z ralph $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Core_Validate_UrlCode extends Zend_Validate_Abstract
{
    const INVALID      = 'codeInvalid';
    const NOT_CODE     = 'notCode';
    const STRING_EMPTY = 'codeStringEmpty';

    /**
     * Alphanumeric filter used for validation
     *
     * @var Zend_Filter_Alnum
     */
    protected static $_filter = null;

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID      => "validate:invalid-type",//"Invalid type given. String, integer or float expected",
        self::NOT_CODE    => "validate:invalid-characters",//"Value contains not allowed characters. Can be letters digits and \"-\"",
        self::STRING_EMPTY => "validate:empty-string"//"'%value%' is an empty string",
    );

    /**
     * Defined by Zend_Validate_Interface.
     *
     * Returns true if and only if $value contains only alphabetic and digit characters
     *
     * @param string $value Value to check.
     *
     * @return boolean
     */
    public function isValid($value)
    {
        if(!is_string($value) && !is_int($value) && !is_float($value))
        {
            $this->_error(self::INVALID);
            return false;
        }

        $this->_setValue($value);

        if('' === $value)
        {
            $this->_error(self::STRING_EMPTY);
            return false;
        }

        $matchResult = preg_match('/[a-zA-Z0-9\-_]+/', $value, $matches);

        if(!$matchResult || $matches[0] != $value)
        {
            $this->_error(self::NOT_CODE);
            return false;
        }
        return true;
    }

}
