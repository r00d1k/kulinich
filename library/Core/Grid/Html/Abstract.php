<?php
abstract class Core_Grid_Html_Abstract
{
    /** @var Core_Entity_Request */
    protected $_source;
    protected $_id;
    protected $_translationSection = null;
    protected $_enableHeader = false;
    protected $_pagination = null;
    protected $_columns = null;
    protected $_defaultOrdering = null;
    protected $_filter = null;
    protected $_rowData = null;

    protected static $counter = 0;


    public function __construct($dataSource=null, $config = null)
    {
        self::$counter++;
        $this->_id = 'grid_'.self::$counter;
        if($dataSource != null)
        {
            $this->setDataSource($dataSource);
        }
        if($config != null)
        {
            $this->setConfig($config);
        }
    }

    public function setFilter($settings)
    {
        $this->_filter = new Core_Grid_Plugin_Filter($settings);
        return $this;
    }

    public function setDefaultOrdering($ordering)
    {
        $this->_defaultOrdering = $ordering;
        return $this;
    }

    public function setDataSource($dataSource)
    {
        if($dataSource instanceof Core_Grid_Source_Abstract || $dataSource instanceof Core_Entity_Request_Abstract)
        {
            $this->_source = $dataSource;
        }
        else
        {
            $this->_source = Core_Grid_Source_Abstract::factory($dataSource);
        }

        return $this;
    }

    public function setConfig($config)
    {
        foreach($config as $parameter => $value)
        {
            $setter = 'set'.ucfirst(strtolower($parameter));
            if(method_exists($this, $setter))
            {
                $this->$setter($value);
            }
        }
        return $this;
    }

    public function setRowData($config)
    {
        $this->_rowData = $config;
        return $this;
    }

    public function getRowData()
    {
        return $this->_rowData;
    }

    public function setColumns($columns)
    {
        if($columns instanceof Core_Grid_Html_CellsSet)
        {
            $this->_columns = $columns;
        }
        elseif(is_array($columns))
        {
            $this->_columns = new Core_Grid_Html_CellsSet($columns);
        }
        return $this;
    }

    public function setPagination($pagination)
    {
        if($pagination instanceof Core_Grid_Html_Pagination)
        {
            $this->_pagination = $pagination;
        }
        elseif(is_array($pagination))
        {
            $this->_pagination = new Core_Grid_Html_Pagination($pagination);
        }
        return $this;
    }

    public function setHeaderenabled($flag)
    {
        if($flag === true || $flag == 1 || $flag == 'yes')
        {
            $this->_enableHeader = true;
        }
        else
        {
            $this->_enableHeader = false;
        }
        return $this;
    }

    public function getHeaderenabled()
    {
        return $this->_enableHeader;
    }

    public function setId($id)
    {
        $this->_id = $this->_translationSection = $id;
        return $this;
    }
    public function getId()
    {
        return $this->_id;
    }

    public function getPagination()
    {
        if($this->_pagination != null)
        {
            $this->_pagination->setGridId($this->getId())->setSource($this->getSource())->setTranslationSection($this->_translationSection);
        }
        return $this->_pagination;
    }

    public function getColumns()
    {
        if($this->_columns != null)
        {
            $this->_columns->setTranslationSection($this->_translationSection)->setGridId($this->getId())->setGrid($this);
        }
        return $this->_columns;
    }

    public function getOrderParams()
    {
        /** @var Core_Grid_Html_CellsSet_Abstract $cell  */
        $cell = null;
        foreach($this->getColumns() as $cell)
        {
            if($cell->isOrderedBy())
            {
                $field = explode(',', $cell->getOrderField());
                $direction = $cell->getOrderDirection();

                foreach($field as $k=>$v)
                {
                    $field[$k] = $v . ' ' . $direction;
                }
                return $field;
            }
        }
        if(!empty($this->_defaultOrdering))
        {
            return array($this->_defaultOrdering);
        }
        return null;
    }

    protected $_sourceParamsApplied = false;

    public function getSource()
    {
        if(!$this->_sourceParamsApplied)
        {
            $order = $this->getOrderParams();

            if(!empty($order))
            {
                foreach($order as $set)
                {
                    $this->_source->order($set);
                }

            }
            $filter = $this->getFilter();
            if(!empty($filter))
            {
                $condition = $filter->applyPlugin($this->_source);
            }
            $this->_sourceParamsApplied = true;
        }

        return $this->_source;
    }

    public function getFilter()
    {
        if($this->_filter != null)
        {
            $this->_filter->setId($this->getId());
        }
        return $this->_filter;
    }

    public function renderGrid()
    {
        return self::getView()->partial(
            'xhtmlGridTable.phtml',
            array(
                'grid' => $this,
                'cells' => $this->getColumns(),
                'pagination' => $this->getPagination(),
                'source' => $this->getSource(),
                'translateSection' => (!empty($this->_translationSection)) ? $this->_translationSection . ':' : ''
            )
        );
    }


    public function __toString()
    {
        return $this->renderGrid();
    }

    protected static $_view = null;
    public static function getView()
    {
        if(null === self::$_view)
        {
            $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
            if($bootstrap->hasResource('view'))
            {
                self::$_view = $bootstrap->getResource('view');
            }
            else
            {
                require_once 'Zend/Controller/Action/HelperBroker.php';
                /* @var $viewRenderer Zend_Controller_Action_Helper_ViewRenderer */
                $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
                $viewRenderer->initView();
                self::$_view = $viewRenderer->view;
            }
            self::$_view->addScriptPath(dirname(__FILE__) . '/Xhtml');
        }
        return self::$_view;
    }
    public static function getTranslate()
    {
        return Zend_Controller_Front::getInstance()->getparam('bootstrap')->getResource('translate');
    }
}











