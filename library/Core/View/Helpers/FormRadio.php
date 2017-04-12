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
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: FormRadio.php 24160 2011-06-28 16:37:04Z adamlundrigan $
 */


/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';


/**
 * Helper to generate a set of radio button elements
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Core_View_Helper_FormRadio extends Zend_View_Helper_FormRadio
{
    /**
     * Form Radio Helper.
     *
     * @param string $name    Name.
     * @param mixed  $value   Value.
     * @param mixed  $attribs Attribs.
     * @param mixed  $options Options.
     * @param string $listsep Separator.
     *
     * @return string
     */
    public function formRadio($name, $value = null, $attribs = null, $options = null, $listsep = "<br />\n")
    {

        $info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
        extract($info); // name, value, attribs, options, listsep, disable

        // retrieve attributes for labels (prefixed with 'label_' or 'label')
        $labelAttribs = array();
        foreach($attribs as $key => $val)
        {
            $tmp = false;
            $keyLen = strlen($key);
            if((6 < $keyLen) && (substr($key, 0, 6) == 'label_'))
            {
                $tmp = substr($key, 6);
            }
            elseif((5 < $keyLen) && (substr($key, 0, 5) == 'label'))
            {
                $tmp = substr($key, 5);
            }

            if($tmp)
            {
                // make sure first char is lowercase
                $tmp[0] = strtolower($tmp[0]);
                $labelAttribs[$tmp] = $val;
                unset($attribs[$key]);
            }
        }

        $labelPlacement = 'append';
        foreach($labelAttribs as $key => $val)
        {
            switch(strtolower($key))
            {
                case 'placement':
                    unset($labelAttribs[$key]);
                    $val = strtolower($val);
                    if(in_array($val, array('prepend', 'append')))
                    {
                        $labelPlacement = $val;
                    }
                    break;

                default:
                    /* Empty statement */
                    break;
            }
        }

        // the radio button values and labels
        $options = (array)$options;

        // build the element
        $xhtml = '';
        $list  = array();

        // should the name affect an array collection?
        $name = $this->view->escape($name);
        if($this->_isArray && ('[]' != substr($name, -2)))
        {
            $name .= '[]';
        }

        // ensure value is an array to allow matching multiple times
        $value = (array)$value;

        // XHTML or HTML end tag?
        $endTag = ' />';
        if(($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml())
        {
            $endTag= '>';
        }

        // Set up the filter - Alnum + hyphen + underscore
        require_once 'Zend/Filter/PregReplace.php';
        $pattern = preg_match('/\pL/u', 'a')
            ? '/[^\p{L}\p{N}\-\_]/u'    // Unicode
            : '/[^a-zA-Z0-9\-\_]/';     // No Unicode
        $filter = new Zend_Filter_PregReplace($pattern, "");

        // add radio buttons to the list.
        foreach($options as $optValue => $optLabel)
        {

            // Should the label be escaped?
            if($escape)
            {
                $optLabel = $this->view->escape($optLabel);
            }

            // is it disabled?
            $disabled = '';
            if(true === $disable)
            {
                $disabled = ' disabled="disabled"';
            }
            elseif(is_array($disable) && in_array($optValue, $disable))
            {
                $disabled = ' disabled="disabled"';
            }

            // is it checked?
            $checked = '';
            if(in_array($optValue, $value))
            {
                $checked = ' checked="checked"';
            }

            // generate ID
            $optId = $id . '-' . $filter->filter($optValue);

            $labelHtml = '<label' . $this->_htmlAttribs($labelAttribs) . ' for="' . $optId . '">' . $optLabel . '</label>';
            // Wrap the radios in labels
            $radio = (('prepend' == $labelPlacement) ? $labelHtml : '')
                    . '<input type="' . $this->_inputType . '" name="' . $name . '" id="' . $optId . '" value="' . $this->view->escape($optValue) . '"'
                    . $checked
                    . $disabled
                    . $this->_htmlAttribs($attribs)
                    . $endTag
                    . (('append' == $labelPlacement) ? $labelHtml : '');

            // add to the array of radio buttons
            $list[] = $radio;
        }

        // done!
        $xhtml .= implode($listsep, $list);

        return $xhtml;
    }
}
