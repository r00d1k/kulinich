<?php
class System_Form_Team extends Core_Form
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
                Core_Form_Element_Text::create('name')
                    ->setLabel('team:name')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Text::create('specification')
                    ->setLabel('team:specification')
                    ->setRequired(false)
            )
            ->addElement(
                Core_Form_Element_Textarea::create('description')
                    ->setLabel('team:description')
                    ->setRequired(false)
            )
            ->addElement(
                Core_Form_Element_Checkbox::create('useBigPhoto')
                    ->setLabel('team:use_big_photo')
                    ->setUncheckedValue('no')
                    ->setCheckedValue('yes')
            )
            ->addElement(
                Core_Form_Element_Image::create('image')
                    ->setLabel('team:image')
                    ->setEndpoint('/system/about/handle-upload/')
                    ->setRequired(true)
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