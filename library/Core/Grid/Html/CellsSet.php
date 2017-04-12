<?php
class Core_Grid_Html_CellsSet implements ArrayAccess, Iterator, Countable
{
    protected $_cells = array();
    protected $_gridId = null;
    protected $_translateSection = null;
    protected $_grid = null;

    public function __construct($options)
    {
        foreach($options as $cell => $settings)
        {
            $cellType = ucfirst(strtolower($settings['type']));
            $cellType = 'Core_Grid_Html_CellsSet_' . $cellType;
            $this->_cells[$cell] = new $cellType($settings);

        }
    }

    public function setGrid($grid)
    {
        $this->_grid = $grid;
        return $this;
    }

    public function getGrid()
    {
        return $this->_grid;
    }

    public function setTranslationSection($section)
    {
        $this->_translateSection = $section;
        return $this;
    }

    public function setGridId($id)
    {
        $this->_gridId = $id;
        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->_cells[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if($this->offsetExists($offset))
        {
            return $this->_cells[$offset]->setTranslationSection($this->_translateSection)->setGridId($this->_gridId);
        }
        else
        {
            return null;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->_cells[$offset] = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->_cells[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    protected $_iteratorCurrent = 0;

    public function current()
    {
        $cells = array_values($this->_cells);
        return $cells[$this->_iteratorCurrent]
            ->setTranslationSection($this->_translateSection)
            ->setGridId($this->_gridId);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->_iteratorCurrent++;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        $cells = array_keys($this->_cells);
        return $cells[$this->_iteratorCurrent];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        $cells = array_keys($this->_cells);
        return isset($cells[$this->_iteratorCurrent]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->_iteratorCurrent = 0;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->_cells);
    }

    public function getRowData($row)
    {
        $rowData = $this->getGrid()->getRowData();
        $out = array();
        if($rowData!=null && is_array($rowData))
        {
            foreach($rowData as $key => $field)
            {
                $out[] = 'data-'.$key . '="' . (($row instanceof Core_Entity_Model_Abstract) ? $row->$field : $row[$field]) . '"';
            }
        }
        return implode(' ', $out);
    }
}