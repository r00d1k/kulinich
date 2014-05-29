<?php
class Auth_Form_Login extends Core_Form
{
    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->setDecorators(
            array(
                new Zend_Form_Decorator_FormElements(),
                array(
                    'HtmlTag',
                    array(
                        'tag'  => 'div',
                        'class'=> 'core-login-form'
                    )
                ),
                new Zend_Form_Decorator_Form()
            )
        );

        $elements['email'] = Core_Form_Element_Text::create('email')
            ->addValidator(new Zend_Validate_EmailAddress())
            ->setLabel('system-users:email')
            ->setAttrib('class', '')
            ->setRequired(true);
        //---------------
        $elements['password'] = Core_Form_Element_Password::create('password')
            ->addValidator(new Zend_Validate_StringLength(array('min' => 3)))
            ->setAttrib('class', '')
            ->setLabel('system-users:password')
            ->setRequired(true);
        //---------------
        //---------------
        $elements['submit'] = Core_Form_Element_Submit::create('submit')
            ->setLabel('auth:sign-in-action')
            ->setAttrib('class', 'btn')
            ->addDecorator('HtmlTag', array('tag'=>'div','class'=>'controls'));

        $mainPart = new Core_Form_SubForm();
        $mainPart->addElements($elements);

        $mainPart->addDisplayGroup(array('remember', 'submit'), 'bottomControls');
        $mainPart
            ->getDisplayGroup('bottomControls')
            ->clearDecorators()
            ->addDecorator('FormElements')
            ->addDecorator('htmlTag', array('tag' => 'div', 'class' => 'form-foot'));

        $this->addSubForm($mainPart, self::MAIN_PART);
    }
}