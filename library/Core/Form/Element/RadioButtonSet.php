<?php
class Core_Form_Element_RadioButtonSet extends Core_Form_Element_Radio
{
    public function init()
    {
        parent::init();
        $this->setDecorators(
            array(
                 'ViewHelper',
                 new Core_Form_Decorator_Buttonset(),
                 new Core_Form_Decorator_FormLine()
            )
        );
        $this->setSeparator(' ');
    }
}
