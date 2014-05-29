<?php
class System_Form_Language extends Core_Form
{
    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $mainPart = new Core_Form_SubForm();
        $mainPart
            ->addElement(
                Core_Form_Element_Text::create('id')
                    ->setLabel('system-languages:id')
                    ->setRequired(true)
                    ->addValidator(
                        new Zend_Validate_StringLength(array('min'=>2,'max'=>2))
                    )
                    ->setAttrib('maxlength','2')
            )
            ->addElement(
                Core_Form_Element_Text::create('name')
                    ->setLabel('system-languages:name')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Text::create('locale')
                    ->setLabel('system-languages:locale')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Checkbox::create('isAvailable')
                    ->setLabel('system-languages:is_available')
                    ->setOptions(array('checkedValue' => 'yes', 'uncheckedValue' => 'no'))
            );
        $this->addSubForm($mainPart, self::MAIN_PART);
        $this->addElement(
            Core_Form_Element_Submit::create('submit')
                ->setLabel('system-languages:action_save')
                ->setAttrib('class', 'btn')
                ->addDecorator('HtmlTag', array('tag'=>'div','class'=>'controls form-foot'))
        );
    }
}