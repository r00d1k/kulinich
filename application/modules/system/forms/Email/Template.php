<?php

class System_Form_Email_Template extends Core_Form
{

    public function init()
    {
        parent::init();
        $this->setAttrib('class', 'system-emails-form form-horizontal');
        $main = new Core_Form_SubForm();

        if(Core_Util_User::hasAccess('system::email-templates', 'create'))
        {
            $code = Core_Form_Element_Text::create('code')
                ->setRequired(true)
                ->setLabel('system-emails:code')
                ->addValidator(new Core_Validate_UrlCode());
            if($this->_entity != null)
            {
                $code->addValidator(new Core_Validate_Unique(array(
                    'field'  => 'code',
                    'entity' => $this->_entity)));
            }
            $main->addElement($code);
        }
        if(Core_Util_User::hasAccess('system::email-templates', 'editVars'))
        {
            $main
                ->addElement(
                    Core_Form_Element_Tags::create('variables')
                    ->setLabel('system-emails:variables')
            );
        }
        $main
            ->addElement(
                Core_Form_Element_Text::create('from')
                ->setRequired(true)
                ->setLabel('system-emails:from')
                ->addValidator(new Zend_Validate_EmailAddress())
            )
            ->addElement(
                Core_Form_Element_RadioButtonSet::create('contentType')
                ->setLabel('system-emails:content_type')
                ->addMultiOptions(
                    array(
                        'text' => 'Text/Plain',
                        'html' => 'Text/Html'
                    )
                )
        );

        $wrapper = new Core_Form_SubFormTabbed();
        foreach(System_Model_Mapper_Language::getInstance()->findAll()as $lng)
        {
            $part = $this->_getTranslationPart()->setLegend($lng->name);
            $part->getElement('languageId')->setValue($lng->id);
            $wrapper->addSubForm($part, $lng->id);
        }
        $main->addSubForm($wrapper, 'locales');

        $this->addSubForm($main, self::MAIN_PART);
        $this->addElement(
            Core_Form_Element_Submit::create('submit')
                ->setLabel('system-emails:action_save')
                ->setAttrib('class', 'btn')
                ->addDecorator('HtmlTag',
                    array(
                    'tag'   => 'div',
                    'class' => 'controls form-foot'))
        );
        $this->populate(
            array(
                self::MAIN_PART => array(
                    'from'        => 'no-reply@' . $_SERVER['HTTP_HOST'],
                    'contentType' => 'text'
                ))
        );
    }

    public function render(Zend_View_Interface $view = null)
    {
        return parent::render($view) . '
            <script type="text/javascript">
                (function($){
                    $("#main_part-contentType-html").change(function(){
                        $(this).parents("form").first().find(".html-body-part").show();
                    });
                    $("#main_part-contentType-text").change(function(){
                        $(this).parents("form").first().find(".html-body-part").hide();
                    });
                    if($("#main_part-contentType-html").attr("checked"))
                    {
                        $("#main_part-contentType-html").parents("form").first().find(".html-body-part").show();
                    }
                })(jQuery);

            </script>
        ';
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
                Core_Form_Element_Text::create('fromName')
                ->setRequired(true)
                ->setLabel('system-emails:from_name')
            )
            ->addElement(
                Core_Form_Element_Text::create('subject')
                ->setRequired(true)
                ->setLabel('system-emails:subject')
            )
            ->addElement(
                Core_Form_Element_Textarea::create('textBody')
                ->setRequired(true)
                ->setLabel('system-emails:text_body')
            )
            ->addElement(
                Core_Form_Element_RichEditor::create('htmlBody', 'innovaBase')
                ->setLabel('system-emails:html_body')
                ->setRequired(true)
                ->addDecorator(
                    'HtmlTag',
                    array(
                    'tag'   => 'div',
                    'class' => 'html-body-part',
                    'style' => 'display:none;'
                    )
                ), 'htmlBody'
        );
        return $form;
    }

    public function isValid($data)
    {
        if(array_keys($data[self::MAIN_PART]))
        {
            $type = !empty($data[self::MAIN_PART]['contentType']) ? $data[self::MAIN_PART]['contentType'] : '';
        }
        else
        {
            $type = !empty($data['contentType']) ? $data['contentType'] : '';
        }

        $locales = $this->getMainForm()->getSubForm('locales')->getSubForms();

        foreach($locales as $locale)
        {
            if($type == System_Model_Mapper_Email_Template::CONTENT_HTML)
            {
                $locale->getElement('htmlBody')->setRequired(true);
            }
            else
            {
                $locale->getElement('htmlBody')->setRequired(false);
            }
        }
        $result = parent::isValid($data);
        foreach($locales as $locale)
        {
            $locale->getElement('htmlBody')->setRequired(true);
        }
        return $result;
    }

}

