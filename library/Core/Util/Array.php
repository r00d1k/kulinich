<?php

/**
 * Core_Util_Array.
 *
 * @class      Core_Util_Array
 * @package    Core_Util
 * @subpackage Core_Util_Array
 */
class Core_Util_Array
{

    /**
     * Convert one level array to hierarchical structure.
     *
     * @param array  $input                 Array to convert.
     * @param string $primaryKeyName        Primary key index name.
     * @param string $parentKeyName         Parent key index name.
     * @param string $childrenContainerName Name of the container where children will be stored.
     *
     * @static
     * @return array
     */
    static public function convertToHierarchical(array $input, $primaryKeyName = 'id', $parentKeyName = 'parent_id', $childrenContainerName = 'children')
    {
        $flat      = array(
        );
        $tree      = array(
        );
        $relations = array(
        );

        foreach($input as $item)
        {
            $relations[$item[$primaryKeyName]] = $item[$parentKeyName];
            $flat[$item[$primaryKeyName]]      = $item;
        }

        foreach($relations as $child => $parent)
        {
            if(!empty($parent))
            {
                $flat[$parent][$childrenContainerName][$child] = & $flat[$child];
            }
            else
            {
                $tree[$child] = & $flat[$child];
            }
        }
        return $tree;
    }

    /**
     * Converting a hierarchical array to use in select.
     *
     * @param array   $input                 Input array.
     * @param string  $keyName               Key field name.
     * @param string  $labelName             Label field name.
     * @param string  $levelMarker           Level marker.
     * @param string  $childrenContainerName Children Container Name.
     * @param integer $level                 Nesting level.
     *
     * @return array
     */
    static public function convertForSelect($input, $keyName, $labelName, $levelMarker = ' ', $childrenContainerName = null, $level = 0)
    {
        $out = array();
        foreach($input as $item)
        {
            $label = ($item instanceof Core_Entity_Model_Abstract) ? $item->$labelName : $item[$labelName];
            $key   = ($item instanceof Core_Entity_Model_Abstract) ? $item->$keyName : $item[$keyName];

            $out[$key] = str_repeat($levelMarker, $level) . $label;
            if($childrenContainerName != null)
            {
                $children = ($item instanceof Core_Entity_Model_Abstract) ? $item->$childrenContainerName : $item[$childrenContainerName];
                if(!empty($children))
                {
                    $out = $out + self::convertForSelect(
                            $children, $keyName, $labelName, $levelMarker,
                            $childrenContainerName, $level + 1);
                }
            }
        }
        return $out;
    }

    /**
     * Remove element from array.
     *
     * @param array &$array Array to remove value from.
     * @param mixed $value  Value to remove.
     *
     * @static
     * @return void
     */
    static public function removeValue(array &$array, $value)
    {
        if(in_array($value, $array))
        {
            array_splice($array, array_search($value, $array), 1, null);
        }
    }

}

