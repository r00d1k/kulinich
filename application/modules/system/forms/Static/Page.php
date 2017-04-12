<?php
class System_Form_Static_Page extends Core_Form
{
    public function init()
    {
        parent::init();
        $this->setAttrib('class','static-pages-form form-horizontal');
        $main = new Core_Form_SubForm();
        if(Core_Util_User::hasAccess('system::static-pages', 'create'))
        {
            $code = Core_Form_Element_Text::create('code')
                ->setRequired(true)
                ->setLabel('static-pages:code')
                ->addValidator(new Core_Validate_UrlCode());
            if($this->_entity != null)
            {
                $code->addValidator(new Core_Validate_Unique(array('field' => 'code', 'entity' => $this->_entity)));
            }
            $main->addElement($code);
        }
        $wrapper = new Core_Form_SubFormTabbed();
        foreach(System_Model_Mapper_Language::getInstance()->findAll()as $lng)
        {
            $part = $this->_getTranslationPart()->setLegend($lng->name);
            $part->getElement('languageId')->setValue($lng->id);
            $wrapper->addSubForm($part, $lng->id);
        }
        $main->addSubForm($wrapper, 'locales');
        $main
            ->addElement(
                Core_Form_Element_Image::create('background')
                    ->setLabel('static-pages:background')
                    ->setEndpoint('/system/static-pages/handle-upload/')
                    ->setRequired(false)
            );

        $this->addSubForm($main, self::MAIN_PART);
        $this->addElement(
            Core_Form_Element_Submit::create('submit')
                ->setLabel('static-pages:action_save')
                ->setAttrib('class', 'btn')
                ->addDecorator('HtmlTag', array('tag'=>'div','class'=>'controls form-foot'))

        );
    }

    protected function _getTranslationPart()
    {
        $form = new Core_Form_SubForm();
        $form
            ->addElement(
                Core_Form_Element_Hidden::create('languageId')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Text::create('title')
                    ->setLabel('static-pages:title')
                    ->setRequired(false)
            )
            ->addElement(
                Core_Form_Element_Tags::create('keywords')
                    ->setLabel('static-pages:keywords')
                    ->setRequired(false)
            )
            ->addElement(
                Core_Form_Element_RichEditor::create('content', 'innovaBase')
                    ->setLabel('static-pages:content')
                    ->setRequired(false)
            );
        return $form;
    }
}