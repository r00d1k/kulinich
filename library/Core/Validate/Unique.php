<?php
class Core_Validate_Unique extends Zend_Validate_Abstract
{
    const USED      = 'used';
    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::USED      => "Entered value already used",
    );
    protected $_field = null;

    /** @var Core_Entity_Model_Abstract null */
    protected $_entity = null;

    public function setEntity(Core_Entity_Model_Abstract $entity)
    {
        $this->_entity = $entity;
        return $this;
    }

    public function setField($field)
    {
        $this->_field = $field;
        return $this;
    }

    /**
     *  Constructor.
     *
     * @param mixed $storageConfig Storage config.
     */
    public function __construct($storageConfig = array())
    {
        foreach($storageConfig as $method => $value)
        {
            $method = 'set'.ucfirst($method);
            if(method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }
    /**
     * Returns true if and only if $value meets the validation requirements.
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param mixed $value   Value to check.
     * @param mixed $context Context.
     *
     * @return boolean
     * @throws Zend_Validate_Exception If validation of $value is impossible.
     */
    public function isValid($value, $context = null)
    {
        if($this->_entity == null)
        {
            throw new Zend_Exception('Unique validator require some model instance to operate with');
        }

        $mapper = $this->_entity->getMapper();
        $key = $this->_entity->getKey();

        $found = $mapper->find(array($this->_field => $value));
        if($found != null)
        {
            if($key == null || $found->getKey() != $key)
            {
                $this->_error(self::USED);
                return false;
            }
        }
        return true;
    }
}