<?php
class Core_Form_Element_Radio extends Zend_Form_Element_Radio
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
        /*$this
            ->addFilter(new Zend_Filter_StringTrim())
            ->addFilter(new Zend_Filter_StripNewlines())
            ->addFilter(new Zend_Filter_StripTags());*/
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
     * @return Core_Form_Element_Text
     */
    public static function create($name)
    {
        $calledClass = get_called_class();
        return new $calledClass($name);
    }
}