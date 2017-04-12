<?php
class Bootstrap extends Core_Application_Bootstrap
{
    protected function _initDefaultAutoloader()
    {
        new Zend_Application_Module_Autoloader(
            array(
                 'namespace' => '',
                 'basePath'  => realpath(dirname(__FILE__))
            )
        );
        $this->bootstrap('locale');
        $locale = $this->getResource('locale');
        $locale->setLocale('ru');
        Core_Entity_Mapper_Abstract::setLocale($locale);
    }
    /**
     * Html compressor initialization.
     *
     * @return void
     */
    protected function _initHtmlCompressor()
    {

        //        Zend_Controller_Front::getInstance()->registerPlugin(new Core_Controller_Plugin_HtmlCompressor());
    }
}