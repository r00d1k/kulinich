<?php
class System_Form_User extends Core_Form
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

        $email = Core_Form_Element_Text::create('email')
            ->addValidator(new Zend_Validate_EmailAddress())
            ->setLabel('system-users:email')
            ->setRequired(true);
        if($this->_entity != null)
        {
            $email->addValidator(new Core_Validate_Unique(array('field' => 'email', 'entity' => $this->_entity)));
        }

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
                $email
            )
            ->addElement(
                Core_Form_Element_Password::create('password')
                    ->addValidator(new Zend_Validate_StringLength(array('min' => 6)))
                    ->setLabel('system-users:password')
            )
            ->addElement(
                Core_Form_Element_Checkbox::create('receiveNotify')
                    ->setLabel('system-users:receive_notifications')
                    ->setUncheckedValue('no')
                    ->setCheckedValue('yes')
            )
            ->addElement(
                Core_Form_Element_Combo::create('language')
                    ->setLabel('system-users:notifications_language')
                    ->setMultiOptions(
                        Core_Util_Array::convertForSelect(
                            System_Model_Mapper_Language::getInstance()->findAll()->toArray(),
                            'id',
                            'name'
                        )
                    )
            )
            ->addElement(
                Core_Form_Element_MultiCombo::create('groups')
                    ->setLabel('system-users:user_groups')
                    ->setMultiOptions(
                        Core_Util_Array::convertForSelect(
                            System_Model_Mapper_Acl_Group::getInstance()->findAll()->toArray(),
                            'id',
                            'name'
                        )
                    )
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