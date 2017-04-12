<?php
class Auth_DeniedController extends Core_Controller_Action_Abstract
{
    /**
     *  Index action.
     * 
     *  @return boolean 
     */
    public function indexAction()
    {
        if($this->getRequest()->isXmlHttpRequest())
        {
            $this->_setLayout('ajax');
            $this->renderScript('denied/index.phtml');
        }
        else
        {

        }
        return true;
    }
}