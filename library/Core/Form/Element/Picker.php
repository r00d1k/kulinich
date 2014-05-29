<?php

    class Core_Form_Element_Picker extends Core_Form_Element_Text
    {
        protected $_pickUrl = null;

        public function setSourceUrl($url)
        {
            $this->_pickUrl = $url;
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
            return parent::render($view).'
            <script type="text/javascript">
                $("#' . $this->getId() . '").userPicker({
                    sourceUrl: "' . $this->_pickUrl .'"
                });
            </script>';
        }
    }
