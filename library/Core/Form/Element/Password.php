<?php
class Core_Form_Element_Password extends Zend_Form_Element_Password
{
    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->setDecorators(array('ViewHelper',new Core_Form_Decorator_FormLine()));
        $this->setAttrib('class', 'span5');
    }
    /**
     * Returns true if element is required.
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->_required;
    }

    /**
     * Creates an element.
     *
     * @param string $name Element name.
     *
     * @return Core_Form_Element_Password
     */
    public static function create($name)
    {
        return new Core_Form_Element_Password($name);
    }
}