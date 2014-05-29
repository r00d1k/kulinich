<?php

/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Mapper_Abstract
 * @subpackage Mapper
 *
 * Abstract Mapper.
 */
abstract class Core_Entity_Mapper_Abstract
{

    const FILTER_COND       = 'cond';
    const FILTER_LOGIC      = 'logic';
    const FILTER_VALUE      = 'value';
    const FILTER_VAL        = 'val';
    const FILTER_FIELD      = 'field';
    /**
     *  1:1 + LEFT JOIN
     */
    const RELATION_PARENT   = 'parent';
    /**
     *  1:1 + LEFT JOIN
     */
    const RELATION_CHILD    = 'child';
    /**
     *  1:n + Lasy load
     */
    const RELATION_CHILDREN = 'children';
    /**
     *  m:n + Lasy load
     */
    const RELATION_M2M      = 'm2m';
    /**
     *  Instances
     *
     * @var array
     */
    const RELATION_LOCALE   = 'locale';

    /**
     * Map parameters
     */
    const MAP_FIELD         = 'field';
    const MAP_CONVERTER     = 'converter';
    const MAP_TYPE          = 'type';
    const MAP_MAPPER        = 'mapper';
    const MAP_MIDDLE_MAPPER = 'middleMapper';
    const MAP_KEY           = 'key';
    const MAP_FOREIGN_KEY   = 'fKey';
    const MAP_ASSOC_KEY     = 'assocKey';
    const MAP_LANGUAGE_KEY  = 'languageKey';
    const MAP_FILTER        = 'filter';
    const MAP_SAVE          = 'save';
    const MAP_LOAD          = 'load';
    const MAP_DELETE        = 'delete';
    const MAP_TO_ARRAY      = 'toArray';
    const MAP_TO_STRING     = 'toString';

    protected static $_relationTypes   = array(
        self::RELATION_CHILD,
        self::RELATION_CHILDREN,
        self::RELATION_PARENT,
        self::RELATION_LOCALE,
        self::RELATION_M2M
    );
    protected $_namespaceMapper = 'Model_Mapper';
    protected $_namespaceForm   = 'Form';
    protected $_namespaceConfig = 'Config';
    protected $_formClass       = null;
    protected static $_instances       = array();
    protected static $_currentLocale   = null;

    /**
     * Setting Locale Code.
     *
     * @param string|Zend_Locale $locale Locale.
     *
     * @return void
     */
    public static function setLocale($locale)
    {
        if($locale instanceof Zend_Locale)
        {
            self::$_currentLocale = strval($locale);
        }
    }

    /**
     * Gets a current Locale Code.
     *
     * @return string
     */
    public static function getLocale()
    {
        return self::$_currentLocale;
    }

    /**
     *  Gets MApper instance.
     *
     * @return Core_Entity_Mapper_Abstract
     */
    public static function getInstance()
    {
        $mapperName = get_called_class();
        if(!isset(self::$_instances[$mapperName]))
        {
            /**
             * @var Core_Entity_Mapper_Abstract
             */
            $mapper = new $mapperName();
            self::$_instances[$mapperName] = &$mapper;
            self::$_instances[$mapperName]->_checkMap();
        }
        return self::$_instances[$mapperName];
    }

    /**
     *  Constructor.
     */
    protected function __construct()
    {
        foreach($this->_map as $bindName => $options)
        {
            if(!empty($options[self::MAP_TYPE]) && $options[self::MAP_TYPE] == self::RELATION_LOCALE)
            {
                if(!isset($options[self::MAP_FILTER]))
                {
                    $options[self::MAP_FILTER]                                   = array();
                }
                $options[self::MAP_FILTER][$options[self::MAP_LANGUAGE_KEY]] = self::getLocale();
                $options[self::MAP_TYPE]                                     = self::RELATION_CHILD;
                $options[self::MAP_SAVE]                                     = false;
                if(!isset($options[self::MAP_LOAD]))
                {
                    $options[self::MAP_LOAD] = true;
                }
                if(!isset($options[self::MAP_TO_ARRAY]))
                {
                    $options[self::MAP_TO_ARRAY] = true;
                }
                $this->_map[$bindName]       = $options;
            }
        }
    }

    /**
     *  Clone.
     *
     *   @return void
     */
    final protected function __clone()
    {

    }

    /**
     *  Fields map
     * @var type
     */
    protected $_map  = array();
    protected $_with = array();

    /**
     * Primary Key field
     * @var type
     */
    protected $_key             = null;
    protected $_modelClass      = 'Core_Entity_Model';
    protected $_storageClass    = 'Core_Entity_Storage_DbSelect';
    protected $_requestClass    = 'Core_Entity_Request';
    protected $_rowsetClass     = 'Core_Entity_Rowset';
    protected $_storageConfig   = array();
    protected $_storageInstance = null;

    /**
     * Gets a data Request Object.
     *
     * @return Core_Entity_Request_Abstract
     */
    public function getDataRequest()
    {
        /** @var Core_Entity_Request $dataRequest  */
        $dataRequest = $this->_requestClass;
        return new $dataRequest(
            array(
            Core_Entity_Model_Abstract::CONSTRUCT_STORAGE => $this->getStorage()
            )
        );
    }

    public function getGrid()
    {
        $req    = $this->getDataRequest();
        $config = $this->getConfig();
        return new Core_Grid_Html($req, $config['grid']);
    }

    /**
     * Gets a model instance by condition.
     *
     * @param mixed $condition Condition to find by.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function find($condition)
    {
        if(!is_array($condition))
        {
            $condition = array($this->getKeyField() => $condition);
        }
        $model     = $this->getStorage()->fetchRow($condition);
        if(!$model->isLoaded())
        {
            return null;
        }
        return $model;
    }

    /**
     * Gets a set of models.
     *
     * @param array|null $filter Data Filter.
     * @param mixed      $order  Order By.
     * @param integer    $limit  Count.
     * @param integer    $offset Offset.
     *
     * @return Core_Entity_Rowset_Abstract.
     */
    public function findAll($filter = array(), $order = null, $limit = null, $offset = null)
    {
        return $this->getStorage()->fetchAll($filter, $order, $limit, $offset)->setAssocKey($this->getKeyField())->enableAssocMode();
    }

    /**
     * Gets a maximal value of field.
     *
     * @param string $alias           Field.
     * @param mixed  $filterCondition Filter.
     *
     * @return integer
     */
    public function getMax($alias, $filterCondition = null)
    {
        return $this->getStorage()->fetchMax($alias, $filterCondition);
    }

    /**
     * Gets a count.
     *
     * @param mixed $filterCondition Filter.
     *
     * @return integer
     */
    public function getCount($filterCondition = null)
    {
        return $this->getStorage()->count($filterCondition);
    }

    /**
     *  Gets a key field.
     *
     * @return string
     */
    public function getKeyField()
    {
        return $this->_key;
    }

    /**
     *  Gets a fields from map.
     *
     * @return array
     */
    public function getFields()
    {
        $out = array();
        foreach($this->_map as $bind => $field)
        {
            if(isset($field[self::MAP_FIELD]))
            {
                $out[$bind] = $field[self::MAP_FIELD];
            }
        }
        return $out;
    }

    /**
     *  Gets a relations config.
     *
     * @return array
     */
    public function getRelations()
    {
        $out = array();
        foreach($this->_map as $bind => $field)
        {
            if(is_array($field) && isset($field[self::MAP_TYPE]) && in_array($field[self::MAP_TYPE],
                    self::$_relationTypes))
            {
                $out[$bind] = $field;
            }
        }
        return $out;
    }

    /**
     * @param $relations Relations to load with.
     *
     * @return Core_Entity_Mapper_Abstract
     * @throws Core_Entity_Exception
     */
    public function with($relations)
    {
        if(!is_array($relations))
        {
            $relations        = array($relations);
        }
        $relationSettings = $this->getRelations();
        //Zend_Debug::dump($relationSettings);exit();
        foreach($relations as $relation)
        {
            if(strpos($relation, '.') !== false)
            {
                $alias    = substr($relation, 0, strpos($relation, '.'));
                $relation = substr($relation, strpos($relation, '.') + 1);
            }
            else
            {
                $alias    = $relation;
                $relation = null;
            }
            if(!array_key_exists($alias, $relationSettings))
            {
                throw new Core_Entity_Exception('Relation "' . $alias . '" is not defined for mapper "' . __CLASS__ . '"');
            }
            if(!in_array($relationSettings[$alias][self::MAP_TYPE],
                    array(self::RELATION_CHILD, self::RELATION_PARENT)))
            {
                throw new Core_Entity_Exception('With can load ony 1:1 relations. Relation "' . $alias . '" for mapper "' . __CLASS__ . '" is not 1:1');
            }
            if(!in_array($alias, $this->_with))
            {
                $this->_with[] = $alias;
            }
            if($relation != null)
            {
                $this->getReferenceMapper($alias)->with($relation);
            }
        }
        return $this;
    }

    public function isWith($alias)
    {
        return in_array($alias, $this->_with);
    }

    /**
     * Looking for tree relations in map.
     *
     * @return \stdClass
     */
    public function getTreeRelations()
    {
        $out           = new stdClass();
        $out->parent   = null;
        $out->children = null;

        foreach($this->_map as $bind => $config)
        {
            if(!is_array($config) || !isset($config[self::MAP_TYPE]) || !in_array($config[self::MAP_TYPE],
                    self::$_relationTypes))
            {
                continue;
            }
            if($config[self::MAP_MAPPER] == get_class($this) && $config[self::MAP_TYPE] == self::RELATION_CHILDREN)
            {
                $out->children = array(
                    'alias'               => $bind,
                    self::MAP_KEY         => $config[self::MAP_KEY],
                    self::MAP_FOREIGN_KEY => $config[self::MAP_FOREIGN_KEY]
                );
            }
            elseif($config[self::MAP_MAPPER] == get_class($this) && $config[self::MAP_TYPE] == self::RELATION_PARENT)
            {
                $out->parent = array(
                    'alias'               => $bind,
                    self::MAP_KEY         => $config[self::MAP_KEY],
                    self::MAP_FOREIGN_KEY => $config[self::MAP_FOREIGN_KEY]
                );
            }
        }
        return $out;
    }

    /**
     * Gets a field Converter.
     *
     * @param string $alias Field name in map.
     *
     * @return Core_Entity_Converter_Abstract
     * @throws Core_Entity_Exception On Error.
     */
    public function getFieldConverter($alias)
    {
        if(!isset($this->_map[$alias][self::MAP_CONVERTER]))
        {
            return null;
        }
        if(is_string($this->_map[$alias][self::MAP_CONVERTER]))
        {
            if(strpos($this->_map[$alias][self::MAP_CONVERTER], '_') === false)
            {
                $this->_map[$alias][self::MAP_CONVERTER] = 'Core_Entity_Converter_' . ucfirst($this->_map[$alias][self::MAP_CONVERTER]);
            }
            $converter                               = $this->_map[$alias][self::MAP_CONVERTER];
            $this->_map[$alias][self::MAP_CONVERTER] = $converter::getInstance();
        }
        if(!($this->_map[$alias][self::MAP_CONVERTER] instanceof Core_Entity_Converter_Interface))
        {
            throw new Core_Entity_Exception('Error instance given as converter for "' . get_class($this) . '::' . $alias . '"');
        }
        return $this->_map[$alias][self::MAP_CONVERTER];
    }

    /**
     * Gets a value from map.
     *
     * @param string $alias      Mape name.
     * @param mixed  $properties Properties.
     *
     * @return string
     */
    public function map($alias = null, $properties = null)
    {
        if($properties != null && $alias != null)
        {
            if(is_string($properties))
            {
                $properties         = array(
                    self::MAP_FIELD => $properties
                );
            }
            $this->_map[$alias] = $properties;
            return $this;
        }
        if(!empty($alias))
        {
            if(!isset($this->_map[$alias]))
            {
                if(stristr($alias, '.'))
                {
                    $nextPart = substr($alias, (strpos($alias, '.')+1));
                    $alias = substr($alias, 0, strpos($alias, '.'));
                    if(!empty($this->_map[$alias][self::MAP_MAPPER]))
                    {
                        $mapped = $this->getReferenceMapper($alias)->map($nextPart);
                        if($mapped != null)
                        {
                            if(!stristr($mapped, '.'))
                            {
                                $mapped = '.'.$mapped;
                            }
                            else
                            {
                                $mapped = ':'.$mapped;
                            }
                            $mapped = $alias . $mapped;
                        }
                        return $mapped;
                    }
                }
                return null;
            }
            elseif(isset($this->_map[$alias][self::MAP_FIELD]))
            {
                return $this->_map[$alias][self::MAP_FIELD];
            }
            else
            {
                return $this->_map[$alias];
            }
        }
        else
        {
            $out = array();
            foreach($this->_map as $alias => $field)
            {
                if(isset($field[self::MAP_FIELD]))
                {
                    $out[$alias] = $field[self::MAP_FIELD];
                }
                else
                {
                    $out[$alias] = $field;
                }
            }
            return $out;
        }
    }

    /**
     *  Gets a reference request.
     *
     * @param string                     $name   Reference map name.
     * @param Core_Entity_Model_Abstract $entity Entity Instance.
     *
     * @return Core_Entity_Request_Abstract
     */
    public function getReferenceRequest($name, Core_Entity_Model_Abstract $entity)
    {
        /** @var Core_Entity_Mapper_Abstract $mapper  */
        $mapper    = $this->_map[$name][self::MAP_MAPPER];
        $mapper    = $mapper::getInstance();
        $selfClass = get_class($this);

        $map = $mapper->_map;

        $result = null;

        switch($this->_map[$name][self::MAP_TYPE])
        {
            case self::RELATION_CHILD:
            case self::RELATION_CHILDREN:
                foreach($mapper->_map as $bindName => $sets)
                {
                    if(isset($sets[self::MAP_LOAD]) && $sets[self::MAP_LOAD] && $sets[self::MAP_TYPE] == self::RELATION_PARENT && $sets[self::MAP_MAPPER] == $selfClass)
                    {
                        unset($mapper->_map[$bindName][self::MAP_LOAD]);
                    }
                }
                //building filter.
                $filter = array();
                if(!empty($this->_map[$name][self::MAP_FILTER]))
                {
                    $filter                                            = $this->_map[$name][self::MAP_FILTER];
                }
                $filter[$this->_map[$name][self::MAP_FOREIGN_KEY]] = $entity->{$this->_map[$name][self::MAP_KEY]};

                /** @var Core_Entity_Request $result  */
                $result = $mapper->_requestClass;
                $result = new $result(
                    array(
                    Core_Entity_Model_Abstract::CONSTRUCT_STORAGE => $mapper->getStorage(),
                    'mapperFilter'                                => $filter
                    )
                );
                break;

            case self::RELATION_PARENT:
                true;
                foreach($mapper->_map as $bindName => $sets)
                {
                    if(isset($sets[self::MAP_LOAD]) && $sets[self::MAP_LOAD] && ($sets[self::MAP_TYPE] == self::RELATION_CHILD || $sets[self::MAP_TYPE] == self::RELATION_CHILDREN) && $sets[self::MAP_MAPPER] == $selfClass)
                    {
                        unset($mapper->_map[$bindName][self::MAP_LOAD]);
                    }
                }
                $filter = array();
                if(!empty($this->_map[$name][self::MAP_FILTER]))
                {
                    $filter                                    = $this->_map[$name][self::MAP_FILTER];
                }
                $filter[$this->_map[$name][self::MAP_KEY]] = $entity->{$this->_map[$name][self::MAP_FOREIGN_KEY]};
                $result                                    = $mapper->_requestClass;
                $result                                    = new $result(
                    array(
                    Core_Entity_Model_Abstract::CONSTRUCT_STORAGE => $mapper->getStorage(),
                    'mapperFilter'                                => $filter
                    )
                );
                break;

            case self::RELATION_M2M:
                /** @var Core_Entity_Mapper_Abstract $middleMapper */
                $middleMapper = $this->_map[$name][self::MAP_MIDDLE_MAPPER];
                /** @var Core_Entity_Mapper_Abstract $middleMapper */
                $middleMapper = $middleMapper::getInstance();

                $targetRelation = $middleMapper->_findRelationToMapperSettings($this->_map[$name][self::MAP_MAPPER],
                    self::RELATION_PARENT);

                $filter = array();
                if(!empty($this->_map[$name][self::MAP_FILTER]))
                {
                    $filter                                            = $this->_map[$name][self::MAP_FILTER];
                }
                $filter[$this->_map[$name][self::MAP_FOREIGN_KEY]] = $entity->{$this->_map[$name][self::MAP_KEY]};

                $keys = $middleMapper->getStorage()->fetchCol($targetRelation[self::MAP_FOREIGN_KEY],
                    $filter);

                $result = $mapper->_requestClass;
                $result = new $result(
                    array(
                    Core_Entity_Model_Abstract::CONSTRUCT_STORAGE => $mapper->getStorage(),
                    'mapperFilter'                                => array(
                        $mapper->getKeyField() => array(
                            self::FILTER_COND  => 'in',
                            self::FILTER_VALUE => $keys,
                        )
                    )
                    )
                );
                break;

            default:
                true;
                break;
        }

        $mapper->_map = $map;
        return $result;
    }

    public function getReferenceMapper($name)
    {
        /** @var Core_Entity_Mapper_Abstract $mapper  */
        $mapper = $this->_map[$name][self::MAP_MAPPER];
        return $mapper::getInstance();
    }

    /**
     *  Gets a reference.
     *
     * @param string                     $name   Reference map name.
     * @param Core_Entity_Model_Abstract $entity Entity Instance.
     *
     * @return Core_Entity_Model_Abstract|Core_Entity_Rowset_Abstract
     */
    public function getReference($name, Core_Entity_Model_Abstract $entity)
    {
        /** @var Core_Entity_Mapper_Abstract $mapper  */
        $mapper    = $this->getReferenceMapper($name);
        $selfClass = get_class($this);

        $map = $mapper->_map;

        $result = null;

        switch($this->_map[$name][self::MAP_TYPE])
        {
            case self::RELATION_CHILD:
            case self::RELATION_CHILDREN:
                foreach($mapper->_map as $bindName => $sets)
                {
                    if(isset($sets[self::MAP_LOAD]) && $sets[self::MAP_LOAD] && $sets[self::MAP_TYPE] == self::RELATION_PARENT && $sets[self::MAP_MAPPER] == $selfClass)
                    {
                        unset($mapper->_map[$bindName][self::MAP_LOAD]);
                    }
                }
                //building filter.
                $filter = array();
                if(!empty($this->_map[$name][self::MAP_FILTER]))
                {
                    $filter = $this->_map[$name][self::MAP_FILTER];
                }
                if($mapper->map($this->_map[$name][self::MAP_FOREIGN_KEY]) == '')
                {
                    throw new Core_Entity_Exception('
                        Incorrect Foreign Key Field set for "' . $name . '" relation
                        from "' . $selfClass . '" to "' . get_class($mapper) . '"
                    ');
                }
                $filter[$this->_map[$name][self::MAP_FOREIGN_KEY]] = $entity->{$this->_map[$name][self::MAP_KEY]};
                //building filter.
                if(self::RELATION_CHILD == $this->_map[$name][self::MAP_TYPE])
                {
                    $result = $mapper->find($filter);
                    if($result == null)
                    {
                        $result = $mapper->getModel();
                    }
                }
                else
                {
                    $result = $mapper->findAll($filter);
                    if(!empty($this->_map[$name]['assocKey']))
                    {
                        $result->setAssocKey($this->_map[$name]['assocKey'])->enableAssocMode();
                    }
                }
                break;

            case self::RELATION_PARENT:
                true;
                foreach($mapper->_map as $bindName => $sets)
                {
                    if(isset($sets[self::MAP_LOAD]) && $sets[self::MAP_LOAD] && ($sets[self::MAP_TYPE] == self::RELATION_CHILD || $sets[self::MAP_TYPE] == self::RELATION_CHILDREN) && $sets[self::MAP_MAPPER] == $selfClass)
                    {
                        unset($mapper->_map[$bindName][self::MAP_LOAD]);
                    }
                }
                $filter = array();
                if(!empty($this->_map[$name][self::MAP_FILTER]))
                {
                    $filter                                    = $this->_map[$name][self::MAP_FILTER];
                }
                $filter[$this->_map[$name][self::MAP_KEY]] = $entity->{$this->_map[$name][self::MAP_FOREIGN_KEY]};
                $result                                    = $mapper->getStorage()->fetchRow($filter);
                break;

            case self::RELATION_M2M:
                /** @var Core_Entity_Mapper_Abstract $middleMapper */
                $middleMapper = $this->_map[$name][self::MAP_MIDDLE_MAPPER];
                /** @var Core_Entity_Mapper_Abstract $middleMapper */
                $middleMapper = $middleMapper::getInstance();

                $targetRelation = $middleMapper->_findRelationToMapperSettings($this->_map[$name][self::MAP_MAPPER],
                    self::RELATION_PARENT);

                $filter = array();
                if(!empty($this->_map[$name][self::MAP_FILTER]))
                {
                    $filter                                            = $this->_map[$name][self::MAP_FILTER];
                }
                $filter[$this->_map[$name][self::MAP_FOREIGN_KEY]] = $entity->{$this->_map[$name][self::MAP_KEY]};

                $keys = $middleMapper->getStorage()->fetchCol($targetRelation[self::MAP_FOREIGN_KEY],
                    $filter);
                if(empty($keys))
                {
                    $result = $mapper->_rowsetClass;
                    $result = new $result(array(), $mapper);
                }
                else
                {
                    $result = $mapper->findAll(
                        array(
                            $mapper->getKeyField() => array(self::FILTER_COND  => 'in', self::FILTER_VALUE => $keys)
                        )
                    );
                }
                break;

            default:
                true;
                break;
        }

        $mapper->_map = $map;
        return $result;
    }

    /**
     * Disconnecting two entities.
     *
     * @param string                                                 $alias        Connection name.
     * @param Core_Entity_Model_Abstract                             $modelSelf    Self Model.
     * @param Core_Entity_Model_Abstract|Core_Entity_Rowset_Abstract $modelRelated Related Model or rowset.
     *
     * @return Core_Entity_Mapper_Abstract.
     * @throws Core_Entity_Exception On Error.
     */
    public function disconnectReference($alias, Core_Entity_Model_Abstract $modelSelf, $modelRelated)
    {
        if(!($modelSelf instanceof $this->_modelClass))
        {
            throw new Core_Entity_Exception('Second argument must a self model instance');
        }
        $options = $this->map($alias);
        switch($options[self::MAP_TYPE])
        {
            case self::RELATION_M2M:
                /** @var Core_Entity_Mapper_Abstract $middleMapper */
                $middleMapper   = $options[self::MAP_MIDDLE_MAPPER];
                $middleMapper   = $middleMapper::getInstance();
                $targetRelation = $modelRelated->getMapper()->_findRelationToMapperSettings(get_class($this),
                    self::RELATION_M2M);

                if($modelRelated instanceof Core_Entity_Model_Abstract)
                {
                    $keys = array($modelRelated->getKey());
                }
                else
                {
                    $keys = array();
                    foreach($modelRelated as $related)
                    {
                        $keys[] = $related->getKey();
                    }
                }

                if(!empty($options[self::MAP_FILTER]))
                {
                    $filter                                         = $options[self::MAP_FILTER];
                }
                $filter[$options[self::MAP_FOREIGN_KEY]]        = $modelSelf->{$options[self::MAP_KEY]};
                $filter[$targetRelation[self::MAP_FOREIGN_KEY]] = array(
                    self::FILTER_COND  => 'in',
                    self::FILTER_VALUE => $keys,
                );
                $referenceMap                                   = $middleMapper->_map;
                foreach($middleMapper->_map as $mapKey => $mapItem)
                {
                    if(!empty($mapItem[self::MAP_LOAD]))
                    {
                        $middleMapper->_map[$mapKey][self::MAP_LOAD] = false;
                    }
                }

                $middleMapper->findAll($filter)->delete();
                $middleMapper->_map = $referenceMap;
                break;

            case self::RELATION_CHILD:
            case self::RELATION_CHILDREN:
                if($modelRelated->{$options[self::MAP_FOREIGN_KEY]} == $modelSelf->{$options[self::MAP_KEY]})
                {
                    $modelRelated->{$options[self::MAP_FOREIGN_KEY]} = null;
                    $modelRelated->save();
                }
                break;

            case self::RELATION_PARENT:
                if($modelSelf->{$options[self::MAP_FOREIGN_KEY]} == $modelRelated->{$options[self::MAP_KEY]})
                {
                    $modelSelf->{$options[self::MAP_FOREIGN_KEY]} = null;
                    $modelSelf->save();
                }
                break;

            default:
                true;
                break;
        }

        return $this;
    }

    /**
     * Checking is models connected.
     *
     * @param string                                                 $alias        Connection name.
     * @param Core_Entity_Model_Abstract                             $modelSelf    Self Model.
     * @param Core_Entity_Model_Abstract|Core_Entity_Rowset_Abstract $modelRelated Related Model or rowset.
     *
     * @return boolean
     * @throws Core_Entity_Exception On Error.
     */
    public function isConnectedReference($alias, Core_Entity_Model_Abstract $modelSelf, $modelRelated)
    {
        if(!($modelSelf instanceof $this->_modelClass))
        {
            throw new Core_Entity_Exception('Second argument must a self model instance');
        }
        $options = $this->map($alias);
        switch($options[self::MAP_TYPE])
        {
            case self::RELATION_M2M:
                /** @var Core_Entity_Mapper_Abstract $middleMapper */
                $middleMapper   = $options[self::MAP_MIDDLE_MAPPER];
                $middleMapper   = $middleMapper::getInstance();
                $targetRelation = $modelRelated->getMapper()->_findRelationToMapperSettings(get_class($this),
                    self::RELATION_M2M);

                if($modelRelated instanceof Core_Entity_Model_Abstract)
                {
                    $key = $modelRelated->getKey();
                }
                else
                {
                    $key = $modelRelated;
                }

                if(!empty($options[self::MAP_FILTER]))
                {
                    $filter                                         = $options[self::MAP_FILTER];
                }
                $filter[$options[self::MAP_FOREIGN_KEY]]        = $modelSelf->{$options[self::MAP_KEY]};
                $filter[$targetRelation[self::MAP_FOREIGN_KEY]] = $key;
                $referenceMap                                   = $middleMapper->_map;
                foreach($middleMapper->_map as $mapKey => $mapItem)
                {
                    if(!empty($mapItem[self::MAP_LOAD]))
                    {
                        $middleMapper->_map[$mapKey][self::MAP_LOAD] = false;
                    }
                }
                $result                                      = ($middleMapper->getCount($filter) > 0);
                break;

            default:
                true;
                break;
        }
        return $result;
    }

    /**
     * Connecting two entities.
     *
     * @param string                                                 $alias        Connection name.
     * @param Core_Entity_Model_Abstract                             $modelSelf    Self Model.
     * @param Core_Entity_Model_Abstract|Core_Entity_Rowset_Abstract $modelRelated Related Model or rowset.
     *
     * @return Core_Entity_Mapper_Abstract.
     * @throws Core_Entity_Exception On Error.
     */
    public function connectReference($alias, Core_Entity_Model_Abstract $modelSelf, $modelRelated)
    {
        if(!($modelSelf instanceof $this->_modelClass))
        {
            throw new Core_Entity_Exception('Second argument must a self model instance');
        }
        $options = $this->map($alias);
        switch($options[self::MAP_TYPE])
        {
            case self::RELATION_M2M:
                /** @var Core_Entity_Mapper_Abstract $middleMapper */
                $middleMapper   = $options[self::MAP_MIDDLE_MAPPER];
                $middleMapper   = $middleMapper::getInstance();
                $targetRelation = $modelRelated->getMapper()->_findRelationToMapperSettings(get_class($this),
                    self::RELATION_M2M);

                $filter = array();
                if(!empty($options[self::MAP_FILTER]))
                {
                    $filter                                  = $options[self::MAP_FILTER];
                }
                $filter[$options[self::MAP_FOREIGN_KEY]] = $modelSelf->{$options[self::MAP_KEY]};
                $keys                                    = $middleMapper->getStorage()->fetchCol($targetRelation[self::MAP_FOREIGN_KEY],
                    $filter);

                if($modelRelated instanceof Core_Entity_Model_Abstract)
                {
                    $modelRelated = array($modelRelated);
                }
                foreach($modelRelated as $related)
                {
                    if(!in_array($related->getKey(), $keys))
                    {
                        $existingConnection                                           = $middleMapper->getModel();
                        $existingConnection->{$options[self::MAP_FOREIGN_KEY]}        = $modelSelf->{$options[self::MAP_KEY]};
                        $existingConnection->{$targetRelation[self::MAP_FOREIGN_KEY]} = $related->{$targetRelation[self::MAP_KEY]};
                        $existingConnection->save();
                    }
                }
                break;

            case self::RELATION_CHILD:
            case self::RELATION_CHILDREN:
                $modelRelated->{$options[self::MAP_FOREIGN_KEY]} = $modelSelf->{$options[self::MAP_KEY]};
                $modelRelated->save();
                break;

            case self::RELATION_PARENT:
                $modelSelf->{$options[self::MAP_FOREIGN_KEY]} = $modelRelated->{$options[self::MAP_KEY]};
                $modelSelf->save();
                break;

            default:
                true;
                break;
        }
        return $this;
    }

    /**
     *  Gets a data storage.
     *
     * @return Core_Entity_Storage_DbSelect
     */
    public function getStorage()
    {
        if($this->_storageInstance == null)
        {
            $storageClass           = $this->_storageClass;
            $this->_storageInstance = new $storageClass($this->_storageConfig);
            $this->_storageInstance->setMapperInstance($this);
        }
        return $this->_storageInstance;
    }

    /**
     *  Gets an empty model Instance.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function getModel()
    {
        $model = $this->getModelClass();
        return new $model(array(Core_Entity_Model_Abstract::CONSTRUCT_MAPPER => $this));
    }

    /**
     *  Gets a model class.
     *
     * @return string
     */
    public function getModelClass()
    {
        return $this->_modelClass;
    }

    /**
     * Gets a rowset class.
     *
     * @return string
     */
    public function getRowsetClass()
    {
        return $this->_rowsetClass;
    }

    /**
     * Gets a storage class.
     *
     * @return string
     */
    public function getStorageClass()
    {
        return $this->_storageClass;
    }

    /**
     *  Checking relations map and generating missing properties.
     *
     * @throws Core_Entity_Exception On error.
     * @return void
     */
    private function _checkMap()
    {
        foreach($this->_map as $alias => $field)
        {
            if(!is_array($field))
            {
                $this->_map[$alias] = array(
                    self::MAP_FIELD => $field
                );
            }
            if(isset($field[self::MAP_TYPE]) && in_array($field[self::MAP_TYPE],
                    self::$_relationTypes))
            {
                switch($field[self::MAP_TYPE])
                {
                    case self::RELATION_PARENT:
                        if(empty($field[self::MAP_KEY]) || empty($field[self::MAP_FOREIGN_KEY]))
                        {
                            $mapper = $field[self::MAP_MAPPER];
                            $mapper = new $mapper();
                        }
                        if(empty($field[self::MAP_KEY]))
                        {
                            $field[self::MAP_KEY] = $mapper->getKeyField();
                        }
                        if(empty($field[self::MAP_FOREIGN_KEY]))
                        {
                            $field[self::MAP_FOREIGN_KEY] = $alias . ucfirst($mapper->getKeyField());
                        }
                        break;

                    case self::RELATION_CHILD:
                    case self::RELATION_CHILDREN:
                        if(empty($field[self::MAP_KEY]))
                        {
                            $field[self::MAP_KEY] = $this->getKeyField();
                        }
                        if(empty($field[self::MAP_FOREIGN_KEY]))
                        {
                            /** @var Core_Entity_Mapper_Abstract */
                            $mapper       = $field[self::MAP_MAPPER];
                            $mapper       = new $mapper();
                            $backRelation = $mapper->_findRelationToMapperSettings(get_class($this),
                                self::RELATION_PARENT);
                            if($backRelation != null && !empty($backRelation[self::MAP_FOREIGN_KEY]))
                            {
                                $field[self::MAP_FOREIGN_KEY] = $backRelation[self::MAP_FOREIGN_KEY];
                            }
                            else
                            {
                                $field[self::MAP_FOREIGN_KEY] = $this->_getPureModelNameField() . ucfirst($this->getKeyField());
                            }
                        }
                        if(!isset($field[self::MAP_SAVE]))
                        {
                            $field[self::MAP_SAVE] = true;
                        }
                        if(!isset($field[self::MAP_DELETE]))
                        {
                            $field[self::MAP_DELETE] = true;
                        }
                        break;

                    case self::RELATION_M2M:
                        /** @var Core_Entity_Mapper_Abstract */
                        $targetMapper = $field[self::MAP_MAPPER];
                        $targetMapper = new $targetMapper();

                        /** @var Core_Entity_Mapper_Abstract */
                        $middleMapper = $field[self::MAP_MIDDLE_MAPPER];
                        $middleMapper = new $middleMapper();
                        if(empty($field[self::MAP_KEY]))
                        {
                            $field[self::MAP_KEY] = $this->getKeyField();
                        }
                        if(empty($field[self::MAP_FOREIGN_KEY]))
                        {
                            $backRelation = $middleMapper->_findRelationToMapperSettings(get_class($this),
                                self::RELATION_PARENT);
                            if($backRelation == null)
                            {
                                throw new Core_Entity_Exception('Middle Mapper Not configured for m2m relation "' . get_class($this) . '->' . get_class($middleMapper) . '"');
                            }
                            if($backRelation != null && !empty($backRelation[self::MAP_FOREIGN_KEY]))
                            {
                                $field[self::MAP_FOREIGN_KEY] = $backRelation[self::MAP_FOREIGN_KEY];
                            }
                            else
                            {
                                $field[self::MAP_FOREIGN_KEY] = $this->_getPureModelNameField() . ucfirst($this->getKeyField());
                            }
                        }
                        $field[self::MAP_SAVE]        = false;
                        $field[self::MAP_DELETE]      = false;
                        break;

                    default:
                        $log = Core_Log_Manager::getInstance()->getLog('default');
                        if(!empty($log))
                        {
                            $log->log("[" . get_class($this) . "]\t" . 'Unknown relation type "' . $field[self::MAP_TYPE] . '"',
                                Zend_Log::NOTICE);
                        }
                        throw new Core_Entity_Exception('Unknown relation type "' . $field[self::MAP_TYPE] . '"');
                        break;
                }
                $this->_map[$alias] = $field;
            }
        }
    }

    /**
     * Looking for relation with given mapper and type.
     *
     * @param string $mapperName   Mapper name.
     * @param string $relationType Relation type.
     *
     * @return null|string
     */
    public function findRelationToMapper($mapperName, $relationType)
    {
        foreach($this->_map as $alias => $options)
        {
            if(is_array($options) && !empty($options[self::MAP_MAPPER]) && $options[self::MAP_MAPPER] == $mapperName && !empty($options[self::MAP_TYPE]) && $options[self::MAP_TYPE] == $relationType)
            {
                return $alias;
            }
        }
        return null;
    }

    /**
     * Looking for relation with given mapper and type.
     *
     * @param string $mapperName   Mapper name.
     * @param string $relationType Relation type.
     *
     * @return null|array
     */
    protected function _findRelationToMapperSettings($mapperName, $relationType)
    {
        foreach($this->_map as $alias => $options)
        {
            if(is_array($options) && !empty($options[self::MAP_MAPPER]) && $options[self::MAP_MAPPER] == $mapperName && !empty($options[self::MAP_TYPE]) && $options[self::MAP_TYPE] == $relationType)
            {
                return $options;
            }
        }
        return null;
    }

    /**
     *  Get a relation field name by mapper name.
     *
     * @return string.
     */
    private function _getPureModelNameField()
    {
        $name = explode('Mapper_', get_class($this));
        $name = explode('_', $name[1]);
        $out  = '';
        foreach($name as $k => $part)
        {
            if($k == 0)
            {
                $out .= strtolower($part);
            }
            else
            {
                $out .= ucfirst($part);
            }
        }
        return $out;
    }

    /**
     *  Gets an entity config.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->_getConfigFile();
    }

    /**
     *  Gets a config from file.
     *
     * @return array
     */
    protected function _getConfigFile()
    {
        $moduleName = get_class($this);
        $moduleName = strtolower(substr($moduleName, 0, strpos($moduleName, '_')));

        if(!in_array($moduleName,
                array_keys(Zend_Controller_Front::getInstance()->getControllerDirectory())))
        {
            $moduleName = "";
        }

        $loader = null;

        foreach(Zend_Loader_Autoloader::getInstance()->getAutoloaders() as $autoloader)
        {
            if(strtolower($autoloader->getNamespace()) == $moduleName)
            {
                $loader = $autoloader;
                break;
            }
        }

        $moduleName = get_class($this);
        $path       = $loader->getResourceTypes();

        $path = $path['configs']['path'];
        if(substr($path, -1) != '/' && substr($path, -1) != '\\')
        {
            $path .= '/';
        }

        $pathPart = str_replace(
            "_", "/",
            substr(
                stristr($moduleName, $this->_namespaceMapper),
                (strlen($this->_namespaceMapper) + 1)
            )
        );

        $config = array();

        foreach(array('ini' => 'Ini', 'yml' => 'Yaml', 'xml' => 'Xml') as $configExt => $parserType)
        {
            if(file_exists($path . $pathPart . '.' . $configExt))
            {
                $parser = 'Zend_Config_' . $parserType;
                $parser = new $parser($path . $pathPart . '.' . $configExt);
                $config = $parser->toArray();
            }
        }
        return $config;
    }

    /**
     *  Get an entity form.
     *
     * @param Core_Entity_Model_Abstract|null $model Optional.
     *
     * @return Core_Form
     * @throws Core_Entity_Exception On error.
     */
    public function getForm($model = null)
    {

        $form = null;
        if(Zend_Loader_Autoloader::getInstance()->autoload($this->getFormClass()))
        {
            $params = array();
            if($model != null)
            {
                $params = array('entity' => $model);
            }
            $form   = new $this->_formClass($params);
        }
        else
        {
            $config = $this->getConfig();
            if(isset($config['form']))
            {
                $config = $config['form'];
                if(!empty($model))
                {
                    $config['model'] = $model;
                }
                $form            = new Core_Form($config);
            }
            else
            {
                throw new Core_Entity_Exception('Form for mapper "' . get_class($this) . '" Not defined');
            }
        }

        return $form;
    }

    /**
     * Sets a form class.
     *
     * @param string $class Form Class name.
     *
     * @return Core_Entity_Mapper
     */
    public function setFormClass($class)
    {
        $this->_formClass = $class;
        return $this;
    }

    /**
     * Gets a gorm class.
     *
     * @return string
     */
    public function getFormClass()
    {
        if(empty($this->_formClass))
        {
            $this->_formClass = str_replace($this->_namespaceMapper,
                $this->_namespaceForm, get_class($this));
        }
        return $this->_formClass;
    }

    /**
     * Gets a field that must be used in __toString.
     *
     * @return array
     */
    public function getToStringFields()
    {
        $out = array();
        foreach($this->_map as $alias => $options)
        {
            if(!empty($options[self::MAP_TO_STRING]))
            {
                $out[] = $alias;
            }
        }
        return $out;
    }

}
