<?php
class System_Form_Clinic_Photo extends Core_Form
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
                Core_Form_Element_Image::create('image')
                    ->setLabel('clinic-photos:image')
                    ->setEndpoint('/system/clinic-cases-gallery/handle-upload/')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Textarea::create('description')
                ->setLabel('clinic-photos:description')
                ->setRequired(false)
            )
            ->addElement(
                Core_Form_Element_Checkbox::create('isCover')
                    ->setLabel('clinic-photos:is_cover')
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