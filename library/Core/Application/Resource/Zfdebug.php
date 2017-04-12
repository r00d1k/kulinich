<?php
/**
 * @category   Core
 * @package    Core_Application
 * @subpackage Resource
 */

require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * ZfDebug resource
 *
 * @uses       Zend_Application_Resource_ResourceAbstract
 * @category   Core
 * @package    Core_Application
 * @subpackage Resource
 */
class Core_Application_Resource_Zfdebug extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @var boolean
     */
    protected $_init = false;

    /**
     * @var boolean
     */
    protected $_enabled = false;

    /**
     * @var array
     */
    protected $_params = array();


    /**
     * Set plugin options
     *
     * @param array $params
     */
    public function setParams( array $params )
    {
        $this->_params = $params;
    }


    /**
     * Return plugin options
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }


    /**
     * Activate plugin
     *
     * @param boolean $enabled
     */
    public function setEnabled( $enabled )
    {
        $this->_enabled = (boolean)$enabled;
    }


    /**
     * Return true iff plugin should be enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->_enabled;
    }


    /**
     * Defined by Zend_Application_Resource_Resource
     */
    public function init()
    {
        $this->initDebugPlugin();
    }


    /**
     * Initialize ZFDebug plugin
     */
    public function initDebugPlugin()
    {
        if( !$this->_init and $this->getEnabled() )
        {
            $this->_init = true;
            $options = $this->getParams();

            if( isset( $options['plugins']['Database']['adapter'] ) )
            {
                if( $this->getBootstrap()->hasPluginResource( 'multiDb' ) )
                {
                    $dbManager = $this->getBootstrap()
                                      ->getApplication()
                                      ->getBootstrap()
                                      ->getResource( 'multiDb' );
                    $adapters = array();
                    foreach( $options['plugins']['Database']['adapter'] as $adapterName )
                    {
                        $adapters[$adapterName] = $dbManager->getDb( $adapterName );
                    }
                    $options['plugins']['Database']['adapter'] = $adapters;
                }
            }

            if( isset( $options['plugins']['Cache'] ) )
            {
                if( $this->getBootstrap()->hasPluginResource( 'cacheManager' ) )
                {
                    $cacheManager = $this->getBootstrap()
                                         ->getApplication()
                                         ->getBootstrap()
                                         ->getResource( 'cacheManager' );

                    $backends = array();
                    $backendNames = $cacheManager->getCacheTemplateNames();
                    foreach( $backendNames as $cacheName )
                    {
                        $backends[$cacheName] = $cacheManager->getCache( $cacheName )->getBackend();
                    }
                    $options['plugins']['Cache']['backend'] = $backends;
                }
            }
            else
            {
                unset( $options['plugins']['Cache'] );
            }

            if( isset( $options['plugins']['File']['base_path'] ) )
            {
                $options['plugins']['File']['base_path'] = realpath( $options['plugins']['File']['base_path'] );
            }

            $debug = new ZFDebug_Controller_Plugin_Debug( $options );

            /** @var $frontController Zend_Controller_Front */
            $frontController = $this->getBootstrap()->getResource( 'frontController' );
            $frontController->registerPlugin( $debug );
        }
    }
}