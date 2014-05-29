<?php
    class Core_Grid_Html_CellsSet_Drag extends Core_Grid_Html_CellsSet_Abstract
    {
        public function renderCell()
        {
            return '<td style="width:10px;"><div class="drag-handler"></div></td>';
        }
    }