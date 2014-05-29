<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Mapper_Abstract
 * @subpackage Mapper
 *
 * Mapper Manager.
 * !!! EXPERIMENTAL FUNCTIONALITY !!!
 */
class Core_Entity_Mapper_Manager
{
    const SYSTEM_MODULE_NAME = 'system';
    protected static $_instance = null;

    /**
     * Gets an instance.
     *
     * @param string|null $currentModule A currently used module. Must be set on a first call.
     *
     * @return Core_Entity_Mapper_Manager
     */
    public static function getInstance($currentModule = null)
    {
        if(self::$_instance == null)
        {
            self::$_instance = new self($currentModule);
        }
        return self::$_instance;
    }

    protected $_currentModule = null;
    protected $_namespaceMapper = 'Model_Mapper';

    /**
     * Constructor.
     *
     * @param string|null $currentModule A currently used module.
     *
     * @throws Core_Entity_Exception On Error.
     */
    protected function __construct($currentModule = null)
    {
        if($currentModule == null && $this->_currentModule == null)
        {
            throw new Core_Entity_Exception('You must specify module name on first getInstance call.');
        }
        $this->_currentModule = $currentModule;
    }

    /**
     * Gets a mapper from current module.
     *
     * @param string $mapper Mapper name.
     *
     * @return Core_entity_Mapper_Abstract
     */
    public function get($mapper)
    {
        return $this->getOther($this->_currentModule, $mapper);
    }

    /**
     * Gets a mapper from system module.
     *
     * @param string $mapper Mapper name.
     *
     * @return Core_entity_Mapper_Abstract
     */
    public function getSystem($mapper)
    {
        return $this->getOther(self::SYSTEM_MODULE_NAME, $mapper);
    }

    /**
     * Gets a mapper from other module.
     *
     * @param string $module Module to get mapper from.
     * @param string $mapper Mapper name.
     *
     * @return Core_entity_Mapper_Abstract
     * @throws Core_Entity_Exception On Error.
     */
    public function getOther($module, $mapper)
    {
        /** @var Core_Entity_Mapper_Abstract $mapper  */
        $mapper = ucfirst($module) . '_' . $this->_namespaceMapper . '_' . $mapper;
        if(!class_exists($mapper))
        {
            Zend_Loader_Autoloader::getInstance()->autoload($mapper);
        }
        if(!class_exists($mapper))
        {
            throw new Core_Entity_Exception('Mapper "' . $mapper . '" not found.');
        }
        return $mapper::getInstance();
    }
}