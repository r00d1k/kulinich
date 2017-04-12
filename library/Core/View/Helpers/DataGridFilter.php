<?php
/**
 * Core_View_Helpers_DataGridExtraFilters.
 *
 * User: Alexander
 * Date: 19.11.11
 * Time: 15:04
 */
class Core_View_Helper_DataGridFilter extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    private $_defaultPartial = 'defaultFilter.phtml';


    /**
     * Render filter.
     *
     * @param Core_DataGrid_Filter $filter  Object filter.
     * @param string|null          $partial Name of partial file.
     *
     * @return string
     */
    public function dataGridFilter(Core_DataGrid_Filter $filter, $partial = null)
    {
        if(empty($partial))
        {
            $partial = $this->_defaultPartial;
        }
        $this->view->addScriptPath(LIB_PATH . '/Core/DataGrid/Renderer/Xhtml');

        return $this->view->partial($partial, array('filter' => $filter));
    }
}
