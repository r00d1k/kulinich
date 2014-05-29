<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Application_Resource_Logmanager
 * @subpackage Resource
 * @uses Zend_Application_Resource_ResourceAbstract
 *
 * Log Manager
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
class Core_Application_Resource_Logmanager extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Core_Log_Manager
     */
    protected $_manager = null;


    /**
     * Initialize Log_Manager.
     *
     * @return Core_Log_Manager
     */
    public function init()
    {
        return $this->getLogManager();
    }


    /**
     * Retrieve Core_Log_Manager instance.
     *
     * @return Core_Log_Manager
     */
    public function getLogManager()
    {
        if(null === $this->_manager)
        {
            $this->_manager = Core_Log_Manager::getInstance();
            $options = $this->getOptions();
            if(isset($options[0]) and empty($options[0]))
            {
                unset($options[0]);
            }
            if(!empty($options))
            {
                foreach($options as $key => $value)
                {
                    $this->_manager->setLogTemplate($key, $value);
                }
            }
        }
        return $this->_manager;
    }
}