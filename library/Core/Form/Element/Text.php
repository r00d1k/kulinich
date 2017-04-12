<?php

class Core_Form_Element_Text extends Zend_Form_Element_Text
{
    protected $_pipeFields = array();
    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->setDecorators(array('ViewHelper',new Core_Form_Decorator_FormLine()));
        $this->setAttrib('class', 'span5');
        $this
            ->addFilter(new Zend_Filter_StringTrim())
            ->addFilter(new Zend_Filter_StripNewlines())
            ->addFilter(new Zend_Filter_StripTags());
    }
    /**
     * Returns true if element is required.
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->_required;
    }

    /**
     * Set fields for pipe.
     *
     * @param mixed $fields Fields list.
     *
     * @return Core_Form_Element_Textarea.
     */
    public function setPipeFields($fields)
    {
        if(empty($fields))
        {
            $fields = array();
        }
        $this->_pipeFields = array_values($fields);
        foreach($this->_pipeFields as $k=>$f)
        {
            $this->_pipeFields[$k] = '{%'.$f.'%}';
        }
        return $this;
    }

    /**
     * Creates an element.
     *
     * @param string $name Element name.
     *
     * @return Core_Form_Element_Text
     */
    public static function create($name)
    {
        $called = get_called_class();
        return new $called($name);
    }
    public function render(Zend_View_Interface $view = null)
    {
        $content = parent::render($view);
        if(!empty($this->_pipeFields))
        {
            return $content.'
            <script type="text/javascript">
                $("#' . $this->getId() . '")
                    .pipeFields({
                        "fields":' . Zend_Json::encode($this->_pipeFields) . ',
                        "label" : "' . addslashes($this->getView()->translate('general:pipe-variable')) . '"
                    })
            </script>
        ';
        }
        return $content;
    }
}
