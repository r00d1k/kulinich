<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Storage_Interface
 *
 * Core Entity Adapter Interface.
 */
interface Core_Entity_Storage_Interface
{
    /**
     * Setting mapper for current storage instance.
     *
     * @param Core_Entity_Mapper_Abstract $instance A mapper instance.
     *
     * @return Core_Entity_Storage_DbSelect_Abstract
     */
    public function setMapperInstance(Core_Entity_Mapper_Abstract $instance);


    /**
     * Gets mapper instance asociated with current storge.
     *
     * @return Core_Entity_Mapper_Abstract
     * @throws Core_Entity_Exception On Error.
     */
    public function getMapper();


    /**
     * Gets a rowset of data from current storage.
     *
     * @param mixed        $condition In format array(field => array(operation, value)).
     * @param string|array $order     String in format 'alias ASD|DESC|Nothing, alias ASD|DESC|Nothing'.
     * @param integer      $count     Count of needed elements.
     * @param integer      $offset    Offset.
     *
     * @return Core_Entity_Rowset_Abstract
     */
    public function fetchAll($condition = array(), $order = null, $count = null, $offset = null);


    /**
     *  Gets a data Row.
     *
     * IMPORTANT. If no rows found this method must return an empty model instance instead of null.
     * Don't forget about isLoaded method of model instances use it if you need to check is model loaded.
     *
     * @param mixed $condition Condition no filter data by.
     *
     * @return Core_Entity_Model_Abstract
     */
    public function fetchRow($condition);


    /**
     *  Counting db rows by cindition.
     *
     * @param mixed $condition Condition.
     *
     * @return integer
     */
    public function count($condition = array());


    /**
     * Gets a maximal value of field.
     *
     * @param string $alias     Field.
     * @param mixed  $condition Filter.
     *
     * @return integer
     */
    public function fetchMax($alias, $condition = null);


    /**
     *  Saving data.
     *
     * @param Core_Entity_Model_Abstract $model A model.to save.
     * @param array                      $data  A data to push to storage.
     *
     * @return boolean
     * @throws Core_Entity_Exception On Error.
     */
    public function save(Core_Entity_Model_Abstract $model, array $data);
}