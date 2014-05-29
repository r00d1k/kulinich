<?php

class Core_Form_Decorator_Buttonset extends Zend_Form_Decorator_Abstract
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
        $id = $this->getElement()->getId();
        return '
            <div id="' . $id . '-buttons">
                ' . $content . '
            </div>
            <script type="text/javascript">
                $("#' . $id . '-buttons").buttonset();
                if(window.console != undefined)
                {
                    console.info("Creating buttonset:", $("#' . $id . '-buttons"));
                }
            </script>
        ';
    }

}

