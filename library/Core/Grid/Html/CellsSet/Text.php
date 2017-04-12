<?php
class Core_Grid_Html_CellsSet_Text extends Core_Grid_Html_CellsSet_Abstract
{
    public function renderCellContent($values)
    {
        return $this->_compileContent($values, $this->_content);
    }

}