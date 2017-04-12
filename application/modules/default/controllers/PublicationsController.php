<?php
class PublicationsController extends Core_Controller_Action_Abstract
{
    public function init()
    {
        parent::init();
    }
    /**
     * Index Action.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->view->articles = System_Model_Mapper_Publication::getInstance()
            ->findAll(array('isEnabled' => 'yes'), 'rank ASC');
    }

    public function viewAction()
    {
        $article = System_Model_Mapper_Publication::getInstance()
            ->find(
                array(
                     'code' => $this->getRequest()->getParam('page-code')
                )
            );
        if($article == null || $article->isEnabled != 'yes')
        {
            throw new Zend_Controller_Action_Exception('Publication Not Found', 404);
        }
        $this->view->article = $article;
    }
}