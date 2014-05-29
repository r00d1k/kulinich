<?php
class Core_Form_Element_Submit extends Zend_Form_Element_Submit
{
    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        $this
            ->setAttrib('class', 'button')
            ->setDecorators(
                array(
                    'ViewHelper',
                )
            );
    }

    /**
     * Creates an element.
     *
     * @param string $name Element name.
     *
     * @return Core_Form_Element_Submit
     */
    public static function create($name)
    {
        return new Core_Form_Element_Submit($name);
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