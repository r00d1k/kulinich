<?php
/**
 * @category   Core
 * @package    Core_Application
 * @subpackage Resource
 */

require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * Cache Manager resource
 *
 * @uses       Zend_Application_Resource_ResourceAbstract
 * @category   Core
 * @package    Core_Application
 * @subpackage Resource
 */
class Core_Application_Resource_Mailer extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Setting up a mailer manager.
     *
     * @return Core_Mailer_Manager|mixed
     */
    public function init()
    {
        $options = $this->getOptions();
        return Core_Mailer_Manager::getInstance($options);
    }
}