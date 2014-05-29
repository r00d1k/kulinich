<?php

class Core_Form_Element_Textarea extends Zend_Form_Element_Textarea
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
        $this->setAttrib('rows', 5);
        $this->setDecorators(array('ViewHelper',new Core_Form_Decorator_FormLine()));
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
     * Creates an element.
     *
     * @param string $name Element name.
     *
     * @return Core_Form_Element_FileUpload
     */
    public static function create($name)
    {
        $class = get_called_class();
        return new $class($name);
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