<?php
class Core_Form_Element_Checkbox extends Zend_Form_Element_Checkbox
{
    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this
                ->setAttrib('class', 'core-form-custom-element')
                ->setDecorators(array('ViewHelper', new Core_Form_Decorator_FormLine()));
    }

    /**
     * Creates an element.
     *
     * @param string $name Element name.
     *
     * @return Core_Form_Element_Checkbox
     */
    public static function create($name)
    {
        return new Core_Form_Element_Checkbox($name);
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
}