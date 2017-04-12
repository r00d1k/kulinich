<?php
class System_Form_Translation extends Core_Form
{
    public function init()
    {
        parent::init();
        $main = new Core_Form_SubForm();

        $main
            ->addElement(
                Core_Form_Element_Text::create('section')
                    ->setRequired(true)
                    ->setLabel('system-translations:section')
            )
            ->addElement(
                Core_Form_Element_Text::create('key')
                    ->setRequired(true)
                    ->setLabel('system-translations:key')
            );

        $wrapper = new Core_Form_SubFormTabbed();
        foreach(System_Model_Mapper_Language::getInstance()->findAll()as $lng)
        {
            $wrapper->addSubForm($this->_getTranslationPart()->setLegend($lng->name), $lng->id);
        }
        $main->addSubForm($wrapper, 'translations');

        $this->addSubForm($main, self::MAIN_PART);
        $this->addElement(
            Core_Form_Element_Submit::create('submit')
                ->setLabel('system-translations:action_save')
                ->setAttrib('class', 'btn')
                ->addDecorator('HtmlTag', array('tag'=>'div','class'=>'controls form-foot'))

        );
    }
    protected function _getTranslationPart()
    {
        $form = new Core_Form_SubForm();
        $form->addElement(
            Core_Form_Element_Textarea::create('value')
                ->setRequired(true)
                ->setLabel('system-translations:value')
        );
        return $form;
    }
}