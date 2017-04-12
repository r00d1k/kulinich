<?php
class Form_Account extends Core_Form
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
                Core_Form_Element_Text::create('firstName')
                    ->setLabel('system-users:first_name')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Text::create('lastName')
                    ->setLabel('system-users:last_name')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Text::create('email')
                    ->addValidator(new Zend_Validate_EmailAddress())
                    ->setLabel('system-users:email')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Password::create('password')
                    ->addValidator(new Zend_Validate_StringLength(array('min' => 6)))
                    ->addValidator(new Zend_Validate_Identical(array('token' => 'passwordConfirm')))
                    ->setLabel('system-users:new_password')
            )
            ->addElement(
                Core_Form_Element_Password::create('passwordConfirm')
                    ->addValidator(new Zend_Validate_StringLength(array('min' => 6)))
                    ->setLabel('system-users:password_confirm')
            );
        $this->addSubForm($mainPart, self::MAIN_PART);
        $this->addElement(
            Core_Form_Element_Submit::create('submit')
                ->setLabel('system-users:action_save')
                ->setAttrib('class', 'btn')
                ->addDecorator('HtmlTag', array('tag'=>'div','class'=>'controls form-foot'))
        );
    }
}