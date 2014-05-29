<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Application_Resource_Locale
 * @subpackage Resource
 * @uses Zend_Application_Resource_Locale
 *
 * Locales + Multilanguage
 */
class Core_Application_Resource_Locale extends Zend_Application_Resource_Locale
{
    /**
     * Defined by Zend_Application_Resource_Resource.
     *
     * @return Zend_Locale
     */
    public function init()
    {
        $options = $this->getOptions();
        if(!empty($options['multilanguage']))
        {
            $plugin = new Core_Controller_Plugin_Multilanguage($options['multilanguage']);
            Zend_Controller_Front::getInstance()->registerPlugin($plugin);
            unset($options['multilanguage']);
            $this->setOptions($options);
        }

        return parent::init();
    }
}