<?php
class LaboratoryController extends Core_Controller_Action_Abstract
{
    /**
     * Index Action.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_setLayout('default');
        $this->view->background = '/res/images/bg/lab.jpg';
        $this->view->photos = System_Model_Mapper_Photo::getInstance()->findAll(
            array(
                 'gallery' => 'lab'
            ),
            'rank ASC'
        );
    }
}