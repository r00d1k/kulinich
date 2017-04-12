<?php
class Core_Form_Decorator_FormLine extends Zend_Form_Decorator_Abstract
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
        $label = $this->getElement()->getLabel();
        $label = empty($label)?'&nbsp;':$label;
        $descr = $this->getOption('descr');
        $errors = $element->getMessages();
        $lineClass = 'control-group';
        $ulErrors = '';
        if(!empty($errors))
        {
            $lineClass .= ' core-form-line-error';
            $ulErrors = '<ul class="core-errors">';
            foreach($errors as $error)
            {
                $ulErrors .= '<li><span class="help-inline">'.$error.'</li>';
            }
            $ulErrors .= '</ul>';
            $lineClass .= ' error';
        }
        if($element->getRequired())
        {
            $label = '<small class="req">*</small> '.$label;
        }
        $html = '
        <div class="'.$lineClass.'">
            <label class="control-label" for="'.$element->id.'">'.$label.':</label>
            <div class="controls">'.$content.$ulErrors.'</div>
        </div>';
        return $html;
    }
}