<?php
/**
 * Usage.
 * 1. Create some count of subforms.
 * 2. Add Legend attribute to all subForms (setLegend()).
 * 3. Add that subforms to SubFormTabbed instance.

 */
class Core_Form_SubFormTabbed extends Zend_Form_SubForm
{
    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        $this->setDecorators(array(new Zend_Form_Decorator_FormElements()))->setIsArray(true);
    }


    /**
     * Overloading of subForm render.
     *
     * @param null|Zend_View_Interface $view View instance.
     *
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        $html = array();
        $tabs = '<ul class="nav nav-tabs">';
        $activeAdded = false;
        foreach($this->getSubForms() as $sub)
        {
            $html[] =
                    $sub->setElementsBelongTo($this->getElementsBelongTo() . '[' . $sub->getName() . ']')
                        ->addDecorators(
                            array(
                                array(
                                    'HtmlTag',
                                    array(
                                        'tag' => 'div',
                                        'class' => 'tab-pane'.((!$activeAdded) ? ' active' : ''),
                                        'id'  => 'core_sub_form_tabbed_' . $sub->getId()
                                    )
                                )
                            )
                        )
                        ->render($view);
            $tabs .= '<li' . ((!$activeAdded) ? ' class="active"' : '') .'><a href="#core_sub_form_tabbed_' . $sub->getId() . '"' . (($sub->isErrors()) ? ' class="tab-error"' : '') . '>' . $sub->getLegend() . '</a></li>';
            $activeAdded = true;
        }
        $tabs .= '</ul>';
        $content = '
            <div id="' . $this->getId() . '_tabbed">
                ' . $tabs . '
                <div class="tab-content">' . implode('', $html) . '</div></div>
            <script type="text/javascript">
                $("#' . $this->getId() . '_tabbed ul a").click(function (e) {
                    e.preventDefault();
                    $(this).tab("show");
                })
                //$(document).ready(function(){$("#' . $this->getId() . '_tabbed").tabs();});
            </script>
        ';

        return $content;
    }
}