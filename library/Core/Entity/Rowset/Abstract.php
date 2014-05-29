<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Rowset_Abstract
 * @subpackage Rowset
 * @use SeekableIterator, Countable, ArrayAccess
 *
 * Entity Rowset.
 */
abstract class Core_Entity_Rowset_Abstract implements SeekableIterator, Countable, ArrayAccess
{
    protected $_mapper = null;
    protected $_mapperClass = null;
    protected $_source = null;
    protected $_assocKey = null;
    protected $_assocModeEnabled = false;
    protected $_position = 0;
    protected $_assocMap = null;

    /**
     *  Constructor.
     *
     * @param mixed                       $dataSource A data source.
     * @param Core_Entity_Mapper_Abstract $mapper     Mapper.
     */
    public function __construct($dataSource, Core_Entity_Mapper_Abstract $mapper)
    {
        $this->_mapper = $mapper;
        $this->_source = $dataSource;
        $this->setAssocKey($mapper->getKeyField());
        $this->init();
    }

    /**
     *  Initialization.
     *
     *  @return void
     */
    public function init()
    {
        $this->setAssocKey($this->getMapper()->getKeyField());
        if(is_array($this->_source))
        {
            $this->_source = array_values($this->_source);
        }
    }

    /**
     *  Gets an assoc map.
     *
     * @return array
     *
     * @throws Zend_Exception On error.
     */
    protected function _getAssocMap()
    {
        if(empty($this->_assocKey))
        {
            throw new Zend_Exception('Assoc Key not set.');
        }
        if($this->_assocMap == null || $this->_assocMap['key'] != $this->_assocKey || $this->_assocMap['map'] == null)
        {
            $this->_assocMap = array(
                'key' => $this->_assocKey,
                'map' => array(),
            );
            foreach($this->_source as $sourceKey => $model)
            {
                if(is_array($model))
                {
                    $model = $this->_source[$sourceKey] = $this->_getModel($sourceKey);
                }
                $this->_assocMap['map'][$model->{$this->_assocKey}] = $sourceKey;
            }
        }
        return $this->_assocMap['map'];
    }

    /**
     *  Sets an assoc key to work with rowset like with assoc array.
     *
     * @param string $key Key.
     *
     * @return Core_Entity_Rowset_Abstract
     */
    public function setAssocKey($key)
    {
        $this->_assocKey = $key;
        if(empty($key))
        {
            $this->disableAssocMode();
        }
        return $this;
    }

    /**
     * Checking is assoc mode enabled.
     *
     * @return boolean
     */
    public function isAssoc()
    {
        return $this->_assocModeEnabled;
    }

    /**
     * Will return a string associated as key, or null if key is not set.
     *
     * @return string|null
     */
    public function getAssocKey()
    {
        return $this->_assocKey;
    }

    /**
     *  Enabling assoc mode.
     *
     * @return Core_Entity_Rowset_Abstract
     */
    public function enableAssocMode()
    {
        if(!empty($this->_assocKey))
        {
            $this->_assocModeEnabled = true;
        }
        return $this;
    }

    /**
     *  Disabling assoc mode.
     *
     * @return Core_Entity_Rowset_Abstract
     */
    public function disableAssocMode()
    {
        $this->_assocModeEnabled = false;
        return $this;
    }

    /**
     *  Gets a mapper.
     *
     * @return Core_Entity_Mapper_Abstract
     */
    public function getMapper()
    {
        if(!($this->_mapper instanceof Core_Entity_Mapper_Abstract))
        {
            $mapper = $this->_mapperClass;
            $this->_mapper = $mapper::getInstance();
        }
        return $this->_mapper;
    }

    /**
     * Counting Rowset.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->_source);
    }

    /**
     *  Gets a current Item.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function current()
    {
        return $this->_getModel($this->_position);
    }

    /**
     * Getting model.
     *
     * @param mixed   $position         Position.
     * @param boolean $assocModeEnabled Get by assoc key.
     *
     *  @return Core_Entity_Model_Astract
     */
    protected function _getModel($position, $assocModeEnabled = false)
    {
        if($assocModeEnabled)
        {
            $map = $this->_getAssocMap();
            if(!isset($map[$position]))
            {
                return null;
            }
            $position = $map[$position];
        }
        else
        {
            $position = (int)$position;
        }

        if(!$this->_isExistsIndex($position))
        {
            return null;
        }
        else
        {
            if(is_array($this->_source[$position]))
            {
                if(method_exists($this->getMapper()->getStorageClass(), 'formatDataRow'))
                {
                    $this->_source[$position] = $this->getMapper()->getStorage()->formatDataRow($this->_source[$position]);
                }
                $model = $this->getMapper()->getModelClass();
                $this->_source[$position] = new $model(
                    array(

                         Core_Entity_Model_Abstract::CONSTRUCT_MAPPER => $this->getMapper(),
                         Core_Entity_Model_Abstract::CONSTRUCT_INITIAL => $this->_source[$position]
                    )
                );
            }
            return $this->_source[$position];
        }
    }

    /**
     *  Gets an entity bu assoc key.
     *
     * @param mixed $key Key.
     *
     * @return Core_Entity_Model_Abstract
     */
    protected function _getModelByAssoc($key)
    {
        $this->_getAssocMap();
        if(!isset($this->_assocMap['map'][$key]))
        {
            return null;
        }
        return $this->_getModel($this->_assocMap['map'][$key]);
    }

    /**
     *  Gets a current key.
     *
     * @return mixed
     */
    public function key()
    {
        if(!$this->_assocModeEnabled)
        {
            return $this->_position;
        }
        else
        {
            return $this->_getModel($this->_position)->{$this->_assocKey};
        }
    }

    /**
     *  Seeking rowset to next record.
     *
     * @return void
     */
    public function next()
    {
        $this->_position += 1;
    }

    /**
     *  Checking is offset exists.
     *
     * @param integer $offset Offset to get.
     *
     * @return bolean
     */
    public function offsetExists($offset)
    {
        if($this->_assocModeEnabled && $this->_assocKey != null)
        {
            $map = $this->_getAssocMap();
            return array_key_exists($offset, $map);
        }
        return $this->_isExistsIndex($offset); //array_key_exists((int)$offset, $this->_source);
    }

    protected function _isExistsIndex($offset)
    {
        return array_key_exists((int)$offset, $this->_source);
    }

    /**
     *  Gets an offset.
     *
     * @param integer $offset Offset to get.
     *
     * @return Core_Entity_Model_Abstract.
     */
    public function offsetGet($offset)
    {
        return $this->_getModel($offset, $this->_assocModeEnabled);
    }

    /**
     * Empty function Do not use it.
     *
     * @param integer $offset Offset.
     * @param mixed   $value  Value.
     *
     * @return null
     */
    final public function offsetSet($offset, $value)
    {
        return null;
    }

    /**
     * Empty function Do not use it.
     *
     * @param integer $offset Offset.
     *
     * @return null
     */
    final public function offsetUnset($offset)
    {
        return null;
    }

    /**
     *  Rewind.
     *
     * @return void
     */
    public function rewind()
    {
        $this->_position = 0;
    }


    /**
     * Seeking rowset to given position.
     *
     * @param integer $position Position to seek to.
     *
     * @throws Core_Entity_Exception When no index found.
     * @return Core_Entity_Rowset_Abstract
     */
    public function seek($position)
    {
        $position = (int)$position;
        if(!array_key_exists($position, $this->_source))
        {
            throw new Core_Entity_Exception("Illegal index $position");
        }
        $this->_position = $position;
        return $this;
    }

    /**
     * Checks is element valid. needed for iterator.
     *
     * @return boolean
     */
    public function valid()
    {
        return array_key_exists($this->_position, $this->_source);
    }

    /**
     *  Setter.
     *
     * @param string $field Field name.
     * @param mixed  $value Field value.
     *
     * @return void
     */
    public function __set($field, $value)
    {
        foreach($this as $k => $model)
        {
            $model->$field = $value;
        }
    }

    /**
     * Gets an array of data.
     *
     * @return array
     */
    public function toArray()
    {
        $out = array();
        if($this->_assocKey == null || !$this->_assocModeEnabled)
        {
            foreach($this as $model)
            {
                $out[] = $model->toArray();
            }
        }
        else
        {
            foreach($this as $key => $model)
            {
                $out[$key] = $model->toArray();
            }
        }
        return $out;
    }

    /**
     *  Sets a data from given array.
     *
     * @param array $data Array of data.
     *
     * @return \Core_Entity_Rowset_Abstract
     */
    public function setArrayData(array $data)
    {
        $currentIsAssoc = $this->isAssoc();
        $currentAssocField = $this->getAssocKey();
        if($currentIsAssoc)
        {
            $this->setAssocKey($this->getMapper()->getKeyField());
            $this->enableAssocMode();
        }
        $keyField = $this->getAssocKey();

        foreach($data as $row)
        {
            /** @var Core_Entity_Model_Abstract $rowEntity  */
            $rowEntity = null;
            if(!empty($row[$keyField]))
            {
                $rowEntity = $this[$row[$keyField]];
            }
            if($rowEntity == null)
            {
                $rowEntity = $this->getMapper()->getModel();
                $this->add($rowEntity);
            }
            $rowEntity->setFormData($row);
        }
        return $this;
    }

    /**
     * Adding Entity to rowset.
     *
     * @param Core_Entity_Model_Abstract $model Model to add.
     *
     * @return Core_Entity_Rowset_Abstract
     * @throws Core_Entity_Exception On Error.
     */
    public function add(Core_Entity_Model_Abstract $model)
    {
        $modelClass = $this
            ->getMapper()
            ->getModelClass();
        if(!($model instanceof $modelClass))
        {
            throw new Core_Entity_Exception('This Rowset Can accept only "' . $this->getMapper->getModelClass() . '" as a model');
        }
        $this->_source[] = $model;
        return $this;
    }

    /**
     *  Saving all entities of current rowset.
     *
     *  @return \Core_Entity_Rowset_Abstract
     */
    public function save()
    {
        foreach($this as $model)
        {
            $model->save();
        }
        $this->_assocMap = null;
        return $this;
    }

    /**
     * Deleting all entities of rowset.
     *
     * @return Core_Entity_Rowset_Abstract
     */
    public function delete()
    {
        foreach($this as $model)
        {
            $model->delete();
        }
        $this->_source = array();
        $this->_assocMap = null;
        return $this;
    }

    /**
     * Gets an array keys.
     *
     * @return array
     */
    public function arrayKeys()
    {
        $keys = array();
        foreach($this as $k => $v)
        {
            $keys[] = $k;
        }
        return array_unique($keys);
    }
}