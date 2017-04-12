<?php
class System_Form_Publication extends Core_Form
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
                    ->setLabel('publications:title')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Text::create('code')
                    ->setLabel('publications:code')
                    ->setRequired(true)
                    ->addValidator(new Core_Validate_UrlCode())
                    ->addValidator(new Core_Validate_Unique(array('field' => 'code', 'entity' => $this->_entity)))
            )
            ->addElement(
                Core_Form_Element_RichEditor::create('content', 'innovaBase')
                    ->setLabel('publications:content')
                    ->setRequired(false)
            )
            ->addElement(
                Core_Form_Element_Image::create('image')
                    ->setLabel('publications:image')
                    ->setEndpoint('/system/publications/handle-upload/')
            )
            ->addElement(
                Core_Form_Element_Checkbox::create('isEnabled')
                    ->setLabel('publications:is_enabled')
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