<?php
/**
 * @package Core_Config
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Config_Manager
 *
 * Main Bootstrap
 */
class Core_Config_Manager
{
    /**
     * Array of configs stored by the Config Manager instance.
     *
     * @var Zend_Config[]
     */
    protected $_configs = array();

    /**
     * Path to configs folder.
     *
     * @var array
     */
    protected $_configTemplates = array();


    /**
     * Set a new config for the Config Manager to contain.
     *
     * @param string      $name   Config name.
     * @param Zend_Config $config Config object.
     *
     * @return Core_Config_Manager
     */
    public function setConfig($name, Zend_Config $config)
    {
        $this->_configs[$name] = $config;
        return $this;
    }


    /**
     * Check if the Config Manager contains the named cache object, or config file exists in file system.
     *
     * @param string $name Config name.
     *
     * @return boolean
     */
    public function hasConfig($name)
    {
        if(isset($this->_configs[$name]) || $this->_configFileExists($name))
        {
            return true;
        }
        return false;
    }


    /**
     * Fetch the named config object, or instantiate and return a config object using a named configuration template.
     *
     * @param string $name Config name.
     *
     * @return Zend_Config
     * @throws Zend_Config_Exception If option templates do not have $name.
     */
    public function getConfig($name)
    {
        if(isset($this->_configs[$name]))
        {
            return $this->_configs[$name];
        }

        /** Try to load config from cache */
        if(!empty($this->_configTemplates[$name]['cache']))
        {
            /** @var $bootstrap Zend_Application_Bootstrap_Bootstrap */
            $bootstrap = Zend_Registry::get('bootstrap');

            /** @var $cacheManager Core_Cache_Manager */
            $cacheManager = $bootstrap->getResource('cacheManager');

            if(!$cacheManager->hasCache($this->_configTemplates[$name]['cache']))
            {
                throw new Zend_Config_Exception("Config with name \"" . $name . "\" has wrong cache specified");
            }

            $cache  = $cacheManager->getCache($this->_configTemplates[$name]['cache']);
            $result = $cache->load(md5($this->_configTemplates[$name]['path']));
            if( $result === false)
            {
                $result = $this->_loadConfig($name);
                $cache->save($result, md5($this->_configTemplates[$name]['path']));
            }
        }
        else
        {
            $result = $this->_loadConfig($name);
        }
        $this->_configs[$name] = $result;

        return $this->_configs[$name];
    }


    /**
     * Loads config.
     *
     * @param string $name Config name.
     *
     * @return Zend_Config
     * @throws Zend_Config_Exception If configuration doesn't exists or provided not supported config type.
     */
    private function _loadConfig($name)
    {
        if($this->_configFileExists($name))
        {

            $suffix = strtolower(pathinfo($this->_configTemplates[$name]['path'], PATHINFO_EXTENSION));

            $configType = null;
            switch($suffix)
            {
                case "ini":
                    $configType = 'Zend_Config_Ini';
                break;

                case "xml":
                    $configType = 'Zend_Config_Xml';
                break;

                case "json":
                    $configType = 'Zend_Config_Json';
                break;

                case "yml":
                case "yaml":
                    $configType = 'Zend_Config_Yaml';
                break;

                default:
                    throw new Zend_Config_Exception("Invalid configuration file provided; unknown config type");
                break;
            }

            return new $configType($this->_configTemplates[$name]['path'], APPLICATION_ENV);
        }
        throw new Zend_Config_Exception('Configuration does not exist with the name "' . $name . '"');
    }


    /**
     * Set a named configuration template from which a config object can later be lazy loaded.
     *
     * @param array $options Config template options.
     *
     * @return Core_Config_Manager
     * @throws Zend_Config_Exception For invalid options format.
     */
    public function setConfigTemplate(array $options)
    {
        if(!is_array($options))
        {
            throw new Zend_Config_Exception('Options passed must be in an associative array');
        }

        foreach($options as $configName => $configParams)
        {
            $this->_configTemplates[$configName] = $configParams;
        }

        return $this;
    }


    /**
     * Check does config file exists.
     *
     * @param string $name Config name.
     *
     * @return boolean
     */
    private function _configFileExists($name)
    {
        $result = false;
        if(isset($this->_configTemplates[$name]['path']) and file_exists($this->_configTemplates[$name]['path']))
        {
            $result = true;
        }

        return $result;
    }
}