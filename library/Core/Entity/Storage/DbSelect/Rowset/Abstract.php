<?php
/**
 * @package Core_Entity
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Entity_Storage_DbSelect_Rowset_Abstract
 * @subpackage Storage_DbSelect
 * @use Core_Entity_Rowset_Abstract
 *
 * Entity Rowset.
 */
abstract class Core_Entity_Storage_DbSelect_Rowset_Abstract extends Core_Entity_Rowset_Abstract
{

    /**
     * Getting model.
     * 
     * @param mixed $position Position.
     * 
     *  @return Core_Entity_Model_Astract 
     */
    protected function _getModel($position)
    {
        $position = (int)$position;
        if(!$this->offsetExists($position))
        {
            return null;
        }
        else
        {
            if(is_array($this->_source[$position]))
            {
                $model = $this->getMapper()->getModelClass();
                $this->_source[$position] = new $model(
                    array(
                        Core_Entity_Model_Abstract::CONSTRUCT_MAPPER => $this->getMapper(),
                        Core_Entity_Model_Abstract::CONSTRUCT_INITIAL => Core_Entity_Storage_DbSelect_Abstract::formatDataRow($this->_source[$position])
                    )
                );
            }
            return $this->_source[$position];
        }
    }
}