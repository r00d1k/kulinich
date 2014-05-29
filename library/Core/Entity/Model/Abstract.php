<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Model_Abstract
 * @subpackage Model
 *
 * Abstract Entity (Model).
 */
abstract class Core_Entity_Model_Abstract
{
    /**
     * This key should be used only from datasource!!
     */
    const CONSTRUCT_INITIAL = 'initialData';

    const CONSTRUCT_MAPPER = 'mapper';
    const CONSTRUCT_STORAGE = 'storage';

    protected $_mapperClass = null;
    protected $_mapperInstance = null;

    protected $_isLoaded = false;
    protected $_isChanged = false;

    protected $_data = array();
    protected $_dataOriginal = array();

    protected $_otherData = array();

    /**
     *  Constructor.
     *
     * @param mixed $initial Initial data.
     */
    public function __construct($initial = array())
    {
        if(is_array($initial))
        {
            foreach($initial as $k => $v)
            {
                if(method_exists($this, 'set'.  ucfirst($k)) && !empty($v))
                {
                    $this->{'set'.  ucfirst($k)}($v);
                }
            }
        }
    }

    /**
     *  Sets an initial data.
     *
     * @param array|boolean $data Data to set.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function setInitialData($data)
    {
        $map = $this->getMapper()->map();
        foreach($data as $field => $value)
        {
            if(!is_array($map[$field]))
            {
                $converter = $this->getMapper()->getFieldConverter($field);
                if($converter != null)
                {
                    $value = $converter->decode($value);
                }
                $this->_data[$field] = $value;
            }
            elseif($map[$field]['type'] == Core_Entity_Mapper_Abstract::RELATION_CHILD || $map[$field]['type'] == Core_Entity_Mapper_Abstract::RELATION_PARENT)
            {
                $mapper = $map[$field]['mapper'];
                $mapper = $mapper::getInstance();

                $model = $mapper->getModelClass();
                $this->_data[$field] = new $model(array(
                    self::CONSTRUCT_MAPPER => $mapper,
                    self::CONSTRUCT_INITIAL => $value
                ));
            }
            elseif($map[$field]['type'] == Core_Entity_Mapper_Abstract::RELATION_CHILDREN)
            {
                $rowset = $this->getMapper()->getRowsetClass();
                $this->_data[$field] = new $rowset($value, $this->getMapper());
            }
        }
        if(!empty($data[$this->getMapper()->getKeyField()]))
        {
            $this->_isLoaded = true;
        }
        return $this;
    }

    /**
     *  Setts mapper instance.
     *
     * @param Core_Entity_Mapper_Abstract $mapper A mapper instance.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function setMapper(Core_Entity_Mapper_Abstract $mapper)
    {
        $this->_mapperInstance = $mapper;
        return $this;
    }

    /**
     *  Gets a mapper instance.
     *
     * @return Core_Entity_Mapper_Abstract
     */
    public function getMapper()
    {
        if($this->_mapperInstance == null)
        {
            $mapperClass = $this->_mapperClass;
            $this->_mapperInstance = $mapperClass::getInstance();
        }
        return $this->_mapperInstance;
    }

    /**
     * Checking is model loaded.
     *
     * @return boolean.
     */
    public function isLoaded()
    {
        return $this->_isLoaded;
    }

    /**
     * Checking is model changed.
     *
     * @return boolean.
     */
    public function isChanged()
    {
        return $this->_isChanged;
    }

    /**
     * Checking is model changed.
     *
     * @return array.
     */
    public function getChanges()
    {
        $out = array();
        foreach(array_keys($this->_dataOriginal) as $alias)
        {
            $out[$alias] = $this->_data[$alias];
        }
        return $out;
    }

    /**
     * Resetting mode to it's initial state.
     *
     * @return boolean.
     */
    public function reset()
    {
        foreach($this->_dataOriginal as $alias => $value)
        {
            $this->_data[$alias] = $value;
        }
        return $this;
    }

    /**
     * Gets a privary key value.
     *
     * @return type
     * @throws Core_Entity_Exception On error.
     */
    public function getKey()
    {
        $key = $this->getMapper()->map($this->getMapper()->getKeyField());
        if(!is_string($key))
        {
            throw new Core_Entity_Exception('Key field must be a real field from map.');
        }
        return $this->{$this->getMapper()->getKeyField()};
    }

    /**
     *  Setter.
     *
     * @param string $name  Attribute name.
     * @param mixed  $value Attribute value.
     *
     * @return void
     */
    public function __set($name, $value)
    {
        if(!is_array($this->getMapper()->map($name)))
        {
            $converter = $this->getMapper()->getFieldConverter($name);
            if($converter != null)
            {
                $value = $converter->check($value);
            }
            $currentValue = null;
            if(isset($this->_data[$name]))
            {
                $currentValue = $this->_data[$name];
            }
            if($currentValue != $value)
            {
                $this->_isChanged = true;
                if(empty($this->_dataOriginal[$name]))
                {
                    $this->_dataOriginal[$name] = $currentValue;
                }
                $this->_data[$name] = $value;
            }
        }
    }

    /**
     * Connecting enities.
     *
     * @param string                                        $alias Connection name.
     * @param Core_Entity_Model|Core_Entity_Rowset_Abstract $model Model to connect.
     *
     * @return Core_Entity_Model
     */
    public function connect($alias, $model)
    {
        $this->getMapper()->connectReference($alias, $this, $model);
        unset($this->_data[$alias]);
        return $this;
    }

    /**
     * Disconnecting enities.
     *
     * @param string                                        $alias Connection name.
     * @param Core_Entity_Model|Core_Entity_Rowset_Abstract $model Model to connect.
     *
     * @return Core_Entity_Model
     */
    public function disconnect($alias, $model)
    {
        $this->getMapper()->disconnectReference($alias, $this, $model);
        unset($this->_data[$alias]);
        return $this;
    }

    /**
     * Disconnecting enities.
     *
     * @param string                                        $alias Connection name.
     * @param Core_Entity_Model|Core_Entity_Rowset_Abstract $model Model to connect.
     *
     * @return Core_Entity_Model
     */
    public function isConnected($alias, $model)
    {
        return $this->getMapper()->isConnectedReference($alias, $this, $model);
    }

    /**
     * Gets a dataRequest for relation.
     *
     * @param string $alias Relation name.
     *
     * @return Core_Entity_Request
     */
    public function getReferenceRequest($alias)
    {
        return $this->getMapper()->getReferenceRequest($alias, $this);
    }

    /**
     *  Getter.
     *
     * @param string $name Attribute name.
     *
     * @return mixed
     */
    public function __get($name)
    {
        $subName = null;
        if(strpos($name, '.'))
        {
            $subName = substr($name, (strpos($name, '.') + 1));
            $name = substr($name, 0, strpos($name, '.'));
        }
        if(!isset($this->_data[$name]))
        {
            $map = $this->getMapper()->map($name);
            if(is_string($map) || isset($map['field']))
            {
                $this->_data[$name] = null;
            }
            else
            {
                $this->_data[$name] = $this->getMapper()->getReference($name, $this);
            }
        }
        if($subName != null && $this->_data[$name] != null && $this->_data[$name] instanceof Core_Entity_Model_Abstract)
        {
            return $this->_data[$name]->$subName;
        }
        return $this->_data[$name];
    }

    /**
     *  Saving an instance.
     *
     * @return boolean
     */
    public function save()
    {
        $result = true;
        if($this->_isChanged)
        {
            $mapper = $this->getMapper();
            $data = array();
            foreach($mapper->getFields() as $bind => $field)
            {
                if(array_key_exists($bind, $this->_dataOriginal))
                {
                    $data[$field] = ((!empty($this->_data[$bind]) || is_numeric($this->_data[$bind]))?$this->_data[$bind]:null);
                    $converter = $this->getMapper()->getFieldConverter($bind);
                    if($converter != null)
                    {
                        $data[$field] = $converter->encode($data[$field]);
                    }
                }
            }
            if(!empty($data))
            {
                $result = $mapper->getStorage()->save($this, $data);
            }
            if($result)
            {
                $this->_data[$this->getMapper()->getKeyField()] = $result;
                $this->_isChanged = false;
                $this->_isLoaded = true;
                foreach($this->getMapper()->getFields() as $bindName => $field)
                {
                    $this->_dataOriginal[$bindName] = (isset($this->_data[$bindName])?$this->_data[$bindName]:null);
                }
                $result = true;
            }
            else
            {
                $result = false;
            }
        }
        if($result)
        {
            foreach($this->getMapper()->getRelations() as $bindName => $config)
            {
                if(isset($config['save']) && $config['save'] && $this->$bindName != null)
                {
                    if($config[Core_Entity_Mapper_Abstract::MAP_TYPE] == Core_Entity_Mapper_Abstract::RELATION_CHILD || $config[Core_Entity_Mapper_Abstract::MAP_TYPE] == Core_Entity_Mapper_Abstract::RELATION_CHILDREN)
                    {
                        $this->$bindName->{$config['fKey']} = $this->{$config['key']};
                    }
                    $this->$bindName->save();
                }
            }
            if(!empty($this->_m2mToInsert) && $this->getKey() != null)
            {
                foreach($this->_m2mToInsert as $alias => $data)
                {
                    $this->_setFormDataM2m($alias, $data);
                }
                $this->_m2mToInsert = array();
            }
        }
        return $result;
    }

    /**
     * Gats a data from model as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $map = $this->getMapper()->map();
        $out = array();
        foreach($map as $bind => $params)
        {
            if(!is_array($params) || isset($params['field']))
            {
                $out[$bind]= isset($this->_data[$bind])?$this->_data[$bind]:null;
            }
            else
            {
                if(isset($this->_data[$bind]) || (isset($params['toArray']) && $params['toArray']))
                {
                    $relatedMapper = $params['mapper'];
                    $relatedMapper = $relatedMapper::getInstance();
                    $originalProperties = array();
                    foreach($relatedMapper->map() as $bindName => $properties)
                    {

                        if(isset($properties[Core_Entity_Mapper_Abstract::MAP_MAPPER]) && $properties[Core_Entity_Mapper_Abstract::MAP_MAPPER] == get_class($this->getMapper()) && isset($properties['toArray']) && $properties['toArray'])
                        {

                            $originalProperties[$bindName] = $properties;
                            $properties['toArray'] = false;
                            $relatedMapper->map($bindName, $properties);
                        }
                    }
                    $out[$bind] = $this->$bind->toArray();
                    foreach($originalProperties as $bindName => $properties)
                    {
                        $relatedMapper->map($bindName, $properties);
                    }
                }
            }
        }
        return $out;
    }

    /**
     *Setting entity data from given array.
     *
     * @param array $data Data to set.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function setArrayData(array $data)
    {
        foreach($data as $bind => $value)
        {
            $options = $this->getMapper()->map($bind);
            if(is_string($options))
            {
                $this->$bind = $value;
            }
            elseif(is_array($options) && !empty($options['type']))
            {
                $item = $this->$bind;
                if(($item instanceof Core_Entity_Model_Abstract) || ($item instanceof Core_Entity_Rowset_Abstract))
                {
                    $item->setArrayData($value);
                }
            }
        }
        return $this;
    }

    /**
     *  Deleting entity.
     *
     * @return void
     */
    public function delete()
    {
        foreach($this->getMapper()->getRelations() as $bindName => $config)
        {
            if(isset($config[Core_Entity_Mapper_Abstract::MAP_DELETE]) && $config[Core_Entity_Mapper_Abstract::MAP_DELETE])
            {
                //if($config[Core_Entity_Mapper_Abstract::MAP_TYPE] != Core_Entity_Mapper_Abstract::RELATION_M2M)
                //{
                    $this->$bindName->delete();
                //}
            }
            if($config[Core_Entity_Mapper_Abstract::MAP_TYPE] == Core_Entity_Mapper_Abstract::RELATION_M2M)
            {
                /** @var Core_Entity_Mapper_Abstract $middleMapper  */
                $middleMapper = $config[Core_Entity_Mapper_Abstract::MAP_MIDDLE_MAPPER];
                $middleMapper::getInstance()
                    ->findAll(
                        array(
                             $config[Core_Entity_Mapper_Abstract::MAP_FOREIGN_KEY] => $this->{$config[Core_Entity_Mapper_Abstract::MAP_KEY]}
                        )
                    )
                    ->delete();
            }
        }
        $this->getMapper()->getStorage()->delete($this->getKey());
        $this->_data = array();
        $this->_dataOriginal = array();
        $this->_isLoaded = false;
        $this->_isChanged = false;
    }



    /**
     *  Gets a form populated by data from current Model.
     *
     * @return Core_Form
     */
    public function getForm()
    {
        $form = $this->getMapper()->getForm($this);
        $form->populate($this->toArray());
        return $form;
    }

    /**
     *  Setting data from form.
     *
     * @param array $data Data to set.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function setFormData(array $data)
    {
        foreach($data as $alias => $value)
        {
            $field = $this->getMapper()->map($alias);
            if(!is_array($field))
            {
                $this->$alias = $value;
            }
            else
            {
                $type = $field[Core_Entity_Mapper_Abstract::MAP_TYPE];
                $this->{'_setFormData'.ucfirst($type)}($alias, $value);
            }
        }
        return $this;
    }

    /**
     * Sets an 1-to-1 parent relation data given From form.
     *
     * @param string $alias Reference name.
     * @param mixed  $data  Data to set.
     *
     * @return void
     */
    protected function _setFormDataParent($alias, $data)
    {
        $this->$alias->setFormData($data);
    }

    /**
     * Sets an 1-to-1 child relation data given From form.
     *
     * @param string $alias Reference name.
     * @param mixed  $data  Data to set.
     *
     * @return void
     */
    protected function _setFormDataChild($alias, $data)
    {
        $this->$alias->setFormData($data);
    }

    /**
     * Sets an 1-to-M Relation data given From form.
     *
     * @param string $alias Reference name.
     * @param mixed  $data  Data to set.
     *
     * @return void
     */
    protected function _setFormDataChildren($alias, $data)
    {
        /** @var Core_Entity_Rowset $rowset  */
        $rowset = $this->$alias;
        if(!$rowset->isAssoc())
        {
            $rowset->setAssocKey($rowset->getMapper()->getKeyField());
            $rowset->enableAssocMode();
        }
        $keyField = $rowset->getAssocKey();

        foreach($data as $row)
        {
            /** @var Core_Entity_Model_Abstract $rowEntity  */
            $rowEntity = null;
            if(!empty($row[$keyField]))
            {
                $rowEntity = $rowset[$row[$keyField]];
            }
            if($rowEntity == null)
            {
                $rowEntity = $rowset->getMapper()->getModel();
                $rowset->add($rowEntity);
            }
            $rowEntity->setFormData($row);
        }
    }

    protected $_m2mToInsert = array();

    /**
     * Sets an M-to-N Relation data given From form.
     *
     * @param string $alias Reference name.
     * @param mixed  $data  Data to set.
     *
     * @return void
     */
    protected function _setFormDataM2m($alias, $data)
    {
        if($this->getKey() == null)
        {
            $this->_m2mToInsert[$alias] = $data;
            return null;
        }
        $properties = $this->getMapper()->map($alias);
        /** @var Core_Entity_Mapper_Abstract $mapper  */
        $mapper = $properties[Core_Entity_Mapper_Abstract::MAP_MAPPER];
        $mapper = $mapper::getInstance();
        if(!empty($data) && is_array($data) && !is_array(current($data)))
        {
            $keys = array();
            if(current($data) instanceof Core_Entity_Model_Abstract)
            {
                foreach($data as $model)
                {
                    $keys[] = $model->getKey();
                }
            }
            else
            {
                $keys = $data;
                $data = $mapper->findAll(
                    array(
                         $mapper->getKeyField() => array(
                             Core_Entity_Mapper_Abstract::FILTER_COND => 'in',
                             Core_Entity_Mapper_Abstract::FILTER_VALUE  => $data
                         )
                    )
                );
            }
            foreach($this->$alias as $related)
            {
                if(!in_array($related->getKey(), $keys))
                {
                    $this->disconnect($alias, $related);
                }
            }
            $this->connect($alias, $data);
        }
    }

    /**
     * Convering current model to string representation. It is not a Serialize!!!.
     *
     * @return string
     */
    public function __toString()
    {
        $title = array();
        foreach($this->getMapper()->getToStringFields() as $alias)
        {
            $item = $this->{$alias};
            if(!empty($item))
            {
                $title[] = trim($item);
            }
        }
        return implode(' ', $title);
    }
}