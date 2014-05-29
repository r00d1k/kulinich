<?php
/**
 * Core_View_Helper_DataGrid.
 *
 * User: Alexander
 * Date: 06.10.11
 * Time: 15:14
 */
class Core_View_Helper_DataGrid extends Zend_View_Helper_Abstract
{
    /**
     * Render data grid.
     *
     * @param Core_DataGrid|null $grid    Object data grid.
     * @param string|null        $partial Name of partial file.
     * @param string|null        $module  Name of module.
     *
     * @return string
     * @throws Zend_View_Exception No data grid instance provided nor found.
     */
    public function dataGrid(Core_DataGrid $grid = null, $partial = null, $module = null)
    {
        if(null === $grid)
        {
            if(!isset( $this->view->dataGrid ) || $this->view->dataGrid instanceof Core_DataGrid)
            {
                require_once 'Zend/View/Exception.php';
                throw new Zend_View_Exception('No data grid instance provided nor found');
            }
            else
            {
                /* @var $grid Core_DataGrid */
                $grid = $this->view->dataGrid;
            }
        }

        if(null === $partial)
        {
            /* @var $renderer Core_DataGrid_Renderer_Xhtml */
            $renderer = $grid->getRenderer();
            if($renderer instanceof Core_DataGrid_Renderer_Xhtml)
            {
                $partial = $renderer->getPartial();
            }

            if(null === $partial)
            {
                require_once 'Zend/View/Exception.php';
                throw new Zend_View_Exception('No view partial provided and no default set');
            }
        }

        $vars = array('grid' => $grid);

        return $this->view->getHelper('partial')->partial($partial, $module, $vars);
    }
}