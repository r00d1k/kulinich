<?php
require_once 'Zend/View/Helper/FormElement.php';
class Core_View_Helper_CustomButton extends Zend_View_Helper_FormElement
{
    /**
     * Custom button.
     * 
     * @param string $name    Name.
     * @param string $value   Value.
     * @param mixed  $attribs Attributes.
     * 
     * @return string
     */
    public function customButton($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info);// name, value, attribs, options, listsep, disable
        if(isset($id) == false)
        {
            $id = '';
        }
        $title = isset($attribs['title'])?$attribs['title']:$name;
        $xhtml = '<input type="submit" style="width: 0; height: 0; padding: 0; margin: 0; border: 0;"><a class="button_big" ' . $this->_htmlAttribs($attribs) . ' id="' . $id . '">' . $title . '</a>';
        return $xhtml;
    }
}
