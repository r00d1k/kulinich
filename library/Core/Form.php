<?php
class Core_Form extends Zend_Form
{
    protected $_entity = null;
    const MAIN_PART = 'main_part';

    public function setEntity($ent)
    {
        $this->_entity = $ent;
    }

    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        $this->setTranslator(Zend_Form::getDefaultTranslator());
        $this->setAction($_SERVER['REQUEST_URI']);
        $this->clearDecorators();
        $this->setDecorators(
            array(
                new Zend_Form_Decorator_FormElements(),
                new Zend_Form_Decorator_Form()
            )
        );
        $this->removeDecorator('Dt');
        $this->removeDecorator('DtDdWrapper');
        $this->setAttrib('class', 'form-horizontal');
    }

    public function getValues($suppressArrayNotation = false)
    {
        $data = parent::getValues($suppressArrayNotation);

        if(count($data) == 1 && array_key_exists(self::MAIN_PART, $data))
        {
            return $data[self::MAIN_PART];
        }

        return $data;
    }

    public function getMainForm()
    {
        return $this->getSubForm(self::MAIN_PART);
    }
}