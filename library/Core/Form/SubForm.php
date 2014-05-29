<?php
class Core_Form_SubForm extends Zend_Form_SubForm
{
    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        $this->setDecorators(array(new Zend_Form_Decorator_FormElements()));
    }
}

