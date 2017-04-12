<?php
/**
 * @author P.Matvienko
 * @file Text.php
 * @created 04.03.13
 */
class Core_Grid_Plugin_Filter_Item_Text extends Core_Grid_Plugin_Filter_Item_Abstract
{
    protected static $_conditions = array(
        'sw' => 'starts_with',
        'ew' => 'ends_with',
        'co' => 'contains',
        'eq' => 'equals'
    );

    public static function getConditions()
    {
        return self::$_conditions;
    }

    public function getValue()
    {
        $data = $this->_getCookieData();
        return $data['value'];
    }

    public function getCondition()
    {
        $data = $this->_getCookieData();
        return $data['condition'];
    }

    protected function _getCookieData()
    {
        $paramName = $this->getId();
        $paramName = (!empty($paramName) ? $paramName . '_' : '') . $this->getName();
        if(!empty($_COOKIE[$paramName]))
        {
            return Zend_Json::decode($_COOKIE[$paramName]);
        }
        return array('value' => null, 'condition' => null);
    }

    public function assembleCondition()
    {
        $value = $this->getValue();
        $cond = $this->getCondition();
        if(empty($value) || empty($cond))
        {
            return null;
        }

        switch($cond)
        {
            case 'sw':
                return array(
                    Core_Entity_Mapper_Abstract::FILTER_FIELD => $this->_field,
                    Core_Entity_Mapper_Abstract::FILTER_COND => 'LIKE',
                    Core_Entity_Mapper_Abstract::FILTER_VALUE => $value.'%',
                );
                break;
            case 'ew':
                return array(
                    Core_Entity_Mapper_Abstract::FILTER_FIELD => $this->_field,
                    Core_Entity_Mapper_Abstract::FILTER_COND => 'LIKE',
                    Core_Entity_Mapper_Abstract::FILTER_VALUE => '%'.$value,
                );
                break;
            case 'co':
                return array(
                    Core_Entity_Mapper_Abstract::FILTER_FIELD => $this->_field,
                    Core_Entity_Mapper_Abstract::FILTER_COND => 'LIKE',
                    Core_Entity_Mapper_Abstract::FILTER_VALUE => '%'.$value.'%',
                );
                break;
            case 'eq':
                return array(
                    Core_Entity_Mapper_Abstract::FILTER_FIELD => $this->_field,
                    Core_Entity_Mapper_Abstract::FILTER_COND => '=',
                    Core_Entity_Mapper_Abstract::FILTER_VALUE => $value,
                );
                break;
            default:
                return null;
                break;
        }
        return null;
    }
}