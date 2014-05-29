<?php
class IndexController extends Core_Controller_Action_Abstract
{
    /**
     * Index Action.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_setLayout('default-index');
    }

    public function staticPageAction()
    {
        $page = $this->getRequest()->getParam('page-code', null);
        if(!empty($page))
        {
            $page = System_Model_Mapper_Static_Page::getInstance()->find(array('code' => $page));
        }
        if($page == null)
        {
            throw new Zend_Exception('Requested page not found',404);
        }
        $locale = $page->locale;
        if($locale == null || $locale->getKey() == null)
        {
            $locale = $page->locales;
            $locale = current($locale);
        }
        $this->view->pageCode = $page->code;
        $this->view->content = $locale->content;
        $this->view->html_keywords = $locale->keywords;
        $this->view->html_title = $locale->title;
        $this->view->background = $page->background;
    }
}