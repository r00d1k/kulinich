<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Application_Resource_Cachemanager
 * @subpackage Resource
 * @uses Zend_Application_Resource_ResourceAbstract
 *
 * Cache Manager
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

class Core_Application_Resource_Cachemanager extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Core_Cache_Manager
     */
    protected $_manager = null;


    /**
     * Initialize Cache_Manager
     *
     * @return Core_Cache_Manager
     */
    public function init()
    {
        return $this->getCacheManager();
    }


    /**
     * Retrieve Core_Cache_Manager instance
     *
     * @return Core_Cache_Manager
     */
    public function getCacheManager()
    {
        if( null === $this->_manager )
        {
            $this->_manager = new Core_Cache_Manager();

            $options = $this->getOptions();
            foreach( $options as $key => $value )
            {
                if( $this->_manager->hasCacheTemplate( $key ) )
                {
                    $this->_manager->setTemplateOptions( $key, $value );
                }
                else
                {
                    $this->_manager->setCacheTemplate( $key, $value );
                }
            }
        }

        return $this->_manager;
    }


    public function getCacheTemplateNames()
    {
        return $this->_manager->getCacheTemplateNames();
    }

}