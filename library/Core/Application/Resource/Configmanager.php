<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Application_Resource_Configmanager
 * @subpackage Resource
 * @uses Zend_Application_Resource_ResourceAbstract
 *
 * Config Manager
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
class Core_Application_Resource_Configmanager extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Core_Config_Manager
     */
    protected $_manager = null;


    /**
     * Initialize Config_Manager
     *
     * @return Core_Config_Manager
     */
    public function init()
    {
        return $this->getConfigManager();
    }


    /**
     * Retrieve Core_Config_Manager instance
     *
     * @return Core_Config_Manager
     */
    public function getConfigManager()
    {
        if( null === $this->_manager )
        {
            $options = $this->getOptions();

            $this->_manager = new Core_Config_Manager();

            $optionsToPass = array();

            foreach( $options as $configName => $configParams )
            {
                if( empty( $configParams['path'] ) )
                {
                    throw new Zend_Config_Exception( "Path for config \"" . $configName . "\" isn't specified" );
                }
                else
                {
                    $optionsToPass[$configName]['path'] = $configParams['path'];
                }

                if( !empty( $configParams['cache'] ) && is_string( $configParams['cache'] ) )
                {
                    $optionsToPass[$configName]['cache'] = $configParams['cache'];
                }

                if( !empty( $configParams['params'] ) )
                {
                    $optionsToPass[$configName]['params'] = $configParams['params'];
                }
            }
            $this->_manager->setConfigTemplate( $optionsToPass );
        }

        return $this->_manager;
    }
}