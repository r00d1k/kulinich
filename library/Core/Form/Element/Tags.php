<?php

class Core_Form_Element_Tags extends Core_Form_Element_Text
{
    public function init()
    {
        parent::init();
    }
    public function render(Zend_View_Interface $view = null)
    {
        return parent::render($view).'
            <script type="text/javascript">
                $("#' . $this->getId() . '").tagit();
            </script>
        ';
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

}
