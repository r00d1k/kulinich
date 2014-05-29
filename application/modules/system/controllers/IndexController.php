<?php
    class System_IndexController extends Core_Controller_Action_Abstract
    {
        public function indexAction()
        {
            $this->redirect('/system/about');
            exit();
        }
    }