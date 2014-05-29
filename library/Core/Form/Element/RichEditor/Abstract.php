<?php
abstract class Core_Form_Element_RichEditor_Abstract  extends Zend_Form_Element_Textarea
{
    protected $_pipeFields = array();
    protected $_editorOptions = null;
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

    public function setEditorOptions($options)
    {
        $this->_editorOptions = $options;
        return $this;
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
}
