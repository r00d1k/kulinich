<?php
class System_Form_Clinic_Case extends Core_Form
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
                Core_Form_Element_Text::create('code')
                    ->setLabel('clinic-photos:code')
                    ->setRequired(true)
                    ->addValidator(new Core_Validate_UrlCode())
                    ->addValidator(new Core_Validate_Unique(array('field' => 'code', 'entity' => $this->_entity)))
            )
            ->addElement(
                Core_Form_Element_Text::create('title')
                    ->setLabel('clinic-photos:title')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Checkbox::create('isEnabled')
                    ->setLabel('clinic-photos:is_enabled')
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