<?php

class Core_Form_Element_Combo extends Zend_Form_Element_Select
{
    protected $_viewPlugin = 'chosen';
    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->setDecorators(array('ViewHelper',new Core_Form_Decorator_FormLine()));
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
     * Creates an element.
     *
     * @param string $name Element name.
     *
     * @return Core_Form_Element_Text
     */
    public static function create($name)
    {
        return new Core_Form_Element_Combo($name);
    }

    public function setViewPlugin($plugin)
    {
        $this->_viewPlugin = $plugin;
        return $this;
    }

    public function render(Zend_View_Interface $view = null)
    {
        $content = parent::render($view);
        if($this->_viewPlugin != null)
        {
            $multiOptions = $this->getMultiOptions();
            $content .= '
            <script type="text/javascript">
                $("#' . $this->getId() . '").' . $this->_viewPlugin . '();';
            if(!is_array($multiOptions) || count($multiOptions < 20))
            {
                $content .= '$("#' . $this->getId() . '").parent().find(".chzn-search").hide()';
            }
            $content .= '</script>
        ';
        }
        return $content;
    }
}
