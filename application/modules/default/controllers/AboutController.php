<?php
class AboutController extends Core_Controller_Action_Abstract
{
    /**
     * Index Action.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_setLayout('default');
        $this->view->background = '/res/images/bg/about.jpg';
        $this->view->photos = System_Model_Mapper_Photo::getInstance()->findAll(
            array(
                 'gallery' => 'about'
            ),
            'rank ASC'
        );
    }
}