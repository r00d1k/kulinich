<?php
class Core_Mailer_Manager
{
    protected static $_instance = null;


    /**
     * Gets a manager instance.
     *
     * @param mixed $settings All adapters settings.
     *
     * @return Core_Mailer_Manager
     */
    public static function getInstance($settings = null)
    {
        if(self::$_instance == null)
        {
            self::$_instance = new self($settings);
        }
        return self::$_instance;
    }
    protected $_settings = array();
    protected $_defaultPreset = null;
    /**
     * Constructor.
     *
     * @param mixed $settings All adapters settings.
     */
    protected function __construct($settings)
    {
        $this->_settings = $settings['presets'];
        $this->_defaultPreset = $settings['default'];
    }


    /**
     * Gets an adapter to send email.
     *
     * @param string $preset A settings preset Name.
     *
     * @return Zend_Mail_Transport_Abstract
     * @throws Zend_Exception On error.
     */
    public function getAdapter($preset = null)
    {
        if($preset == null)
        {
            $preset = $this->_defaultPreset;
        }
        if(!isset($this->_settings[$preset]))
        {
            throw new Zend_Exception('Adapter preset "' . $preset . '" not configured');
        }
        else
        {
            if(in_array($this->_settings[$preset]['type'], array('smtp')))
            {
                return $this->_getZendMailTransport($this->_settings[$preset]['type'], $this->_settings[$preset]['options']);
            }
        }
    }


    /**
     * Gets a Zend Mail transport.
     *
     * @param string $type    Transport type.
     * @param mixed  $options Transport options.
     *
     * @return Zend_Mail_Transport_Abstract
     */
    protected function _getZendMailTransport($type, $options)
    {
        $transport = 'Zend_Mail_Transport_' . ucfirst($type);
        $transport = new $transport($options['host'], $options['connection']);
        return $transport;
    }
}