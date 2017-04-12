<?php
class Core_Form_Decorator_ComboList extends Zend_Form_Decorator_Abstract
{
    /**
     * Renderer.
     *
     * @param string $content Html Code Of tag.
     *
     * @return string $html A wrapped html element
     */
    public function render($content)
    {
        $element = $this->getElement();

        return $content .'
        <script type="text/javascript">
            $("#' . $element->getId() . '").comboList();
        </script>';
    }
}