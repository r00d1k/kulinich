<?php
class Core_View_Helper_Navigation_Breadcrumbs extends Zend_View_Helper_Navigation_Breadcrumbs
{
    /** @var array */
    protected $_subCrumbs = array();

    /**
     * Setting Sub Pages.
     *
     * @param mixed $subCrumbs Array of sub pages.
     *
     * @return Core_View_Helper_Navigation_Breadcrumbs
     */
    public function setSubPages($subCrumbs)
    {
        $this->resetSubPages();
        foreach($subCrumbs as $subPage)
        {
            $this->addSubPage($subPage);
        }
        return $this;
    }

    /**
     * Adding a sub crumb.
     *
     * @param array|Core_Navigation_Page_Abstract $subPage PAge to add.
     *
     * @return Core_View_Helper_Navigation_Breadcrumbs
     */
    public function addSubPage($subPage)
    {
        $this->_subCrumbs[] = $subPage;
        return $this;
    }

    /**
     * Resetting dub crumbs.
     *
     * @return Core_View_Helper_Navigation_Breadcrumbs
     */
    public function resetSubPages()
    {
        $this->_subCrumbs = array();
        return $this;
    }

    /**
     * Renders helper.
     *
     * @param Zend_Navigation_Container|null $container Optional. container to render.
     *
     * @return string
     */
    public function render(Zend_Navigation_Container $container = null)
    {
        if($container == null)
        {
            $container = $this->getContainer();
        }
        $invisiblePages = $container->findAllBy('visible', false);
        foreach($invisiblePages as $pg)
        {
            $pg->set('visible', true);
        }
        if(count($this->_subCrumbs) != 0)
        {
            /** @var Zend_Navigation_Page_Mvc $activePage */
            $activePage = $container->findBy('active', true);
            if(!empty($activePage))
            {
                $data = $activePage->toArray();
                $lastPage = $activePage;
                foreach($this->_subCrumbs as $item)
                {
                    if(is_array($item))
                    {
                        $item = Zend_Navigation_Page::factory(array_merge($data, $item));
                    }
                    if($item instanceof Zend_Navigation_Page)
                    {
                        $lastPage->addPage($item);
                        $item->setParent($lastPage);
                        $lastPage = $item;
                    }
                }
                $activePage->set('active', false);
                $lastPage->set('active', true);
            }
        }
        return parent::render($container);
    }
}