<?php
/**
 * @category   Core
 * @package    Core_Log
 */

/**
 * @category   Core
 * @package    Core_Log
 */
class Core_Log_Manager
{
    protected static $_instance = null;

    public static function getInstance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function __construct()
    {

    }
    /**
     * Array of logs stored by the Log Manager instance
     *
     * @var array
     */
    protected $_logs = array();


    /**
     * Array of configuration templates for lazy loading logs.
     *
     * @var array
     */
    protected $_optionTemplates = array();


    /**
     * Set a new log for the Log Manager to contain
     *
     * @param  string $name
     * @param  Zend_Log $log
     * @return Core_Log_Manager
     */
    public function setLog( $name, Zend_Log $log )
    {
        $this->_logs[$name] = $log;
        return $this;
    }


    /**
     * Check if the Log Manager contains the named cache object, or a named
     * configuration template to lazy load the log object
     *
     * @param string $name
     * @return bool
     */
    public function hasLog( $name )
    {
        if( isset( $this->_logs[$name] ) || $this->hasLogTemplate( $name ) )
        {
            return true;
        }
        return false;
    }


    /**
     * Fetch the named log object, or instantiate and return a log object
     * using a named configuration template
     *
     * @param  string $name
     * @return Zend_Log
     * @throws Zend_Log_Exception if option templates do not have $name
     */
    public function getLog( $name )
    {
        if( isset( $this->_logs[$name] ) )
        {
            return $this->_logs[$name];
        }
        if( isset( $this->_optionTemplates[$name] ) )
        {

            $this->_logs[$name] = Zend_Log::factory( $this->_optionTemplates[$name] );

            return $this->_logs[$name];
        }
        throw new Zend_Log_Exception( 'A log configuration template does not exist with the name "' . $name . '"' );
    }


    /**
     * Set a named configuration template from which a log object can later be lazy loaded
     *
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Core_Log_Manager
     * @throws Zend_Log_Exception for invalid options format
     */
    public function setLogTemplate( $name, $options )
    {
        if( $options instanceof Zend_Config )
        {
            $options = $options->toArray();
        }
        elseif( !is_array( $options ) )
        {
            require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception( 'Options passed must be in an associative array or instance of Zend_Config' );
        }
        $this->_optionTemplates[$name] = $options;
        return $this;
    }


    /**
     * Check if the named configuration template
     *
     * @param  string $name
     * @return bool
     */
    public function hasLogTemplate( $name )
    {
        if( isset( $this->_optionTemplates[$name] ) )
        {
            return true;
        }
        return false;
    }


    /**
     * Get the named configuration template
     *
     * @param  string $name
     * @return array
     * @throws Zend_Log_Exception if option templates do not have $name
     */
    public function getLogTemplate( $name )
    {
        if( isset( $this->_optionTemplates[$name] ) )
        {
            return $this->_optionTemplates[$name];
        }
        throw new Zend_Log_Exception( 'A log configuration template does not exist with the name "' . $name . '"' );
    }
}