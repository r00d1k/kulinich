<?php
class System_Form_Photo extends Core_Form
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
                Core_Form_Element_Text::create('title')
                    ->setLabel('photos:title')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Textarea::create('description')
                    ->setLabel('photos:description')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Image::create('image')
                    ->setLabel('photos:image')
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