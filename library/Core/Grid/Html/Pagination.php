<?php
class Core_Grid_Html_Pagination
{
    protected $_pageSize = 20;
    protected $_gridId = null;
    /** @var Core_Entity_Request_Abstract null */
    protected $_source = null;
    protected $_translateSection = null;

    public function __construct($settings = array())
    {
        foreach($settings as $parameter => $value)
        {
            $setter = 'set'.ucfirst(strtolower($parameter));
            if(method_exists($this, $setter))
            {
                $this->$setter($value);
            }
        }
    }

    public function setSource($source)
    {
        $this->_source = $source;
        return $this;
    }

    public function getPage()
    {
        $page = 'page';
        if($this->_gridId != null)
        {
            $page .= '_' . $this->_gridId;
        }
        return Zend_Controller_Front::getInstance()->getRequest()->getParam($page, 1);
    }

    public function getPageData()
    {
        $this->_source->limitPerPage($this->_pageSize);
        return $this->_source->getPage($this->getPage());
    }

    public function getPagesCount()
    {
        $this->_source->limitPerPage($this->_pageSize);
        return $this->_source->countPages();
    }

    public function setPageSize($size)
    {
        $this->_pageSize = $size;
        return $this;
    }
    public function getPageSize()
    {
        return $this->_pageSize;
    }

    public function setGridId($id)
    {
        $this->_gridId = $id;
        return $this;
    }

    public function setTranslationSection($section)
    {
        $this->_translateSection = $section;
        return $this;
    }

    public function render()
    {
        return Core_Grid_Html_Abstract::getView()->partial(
            'xhtmlGridPages.phtml',
            array(
                'pagination' => $this,
                'gridId' => $this->_gridId,
                'translateSection' => (!empty($this->_translationSection)) ? $this->_translationSection . ':' : '',
                'currentPage' => $this->getPage(),
                'pagesCount' => $this->getPagesCount()
            )
        );
    }
}