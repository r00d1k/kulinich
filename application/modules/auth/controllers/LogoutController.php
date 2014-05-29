<?php
class Auth_LogoutController extends Core_Controller_Action_Abstract
{
    /**
     *  Logs user out.
     *
     * @return boolean
     */
    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        Zend_Session::forgetMe();

        $returnTo = $this->getRequest()->getParam('return-to', null);
        if(!empty($returnTo))
        {
            $returnTo = urldecode($returnTo);
        }
        if(empty($returnTo))
        {
            $returnTo = $this->view->url(
                array(
                    'module'    => 'default',
                    'controller'=> 'index',
                    'action'    => 'index'
                ),
                'default',
                true
            );
        }
        return $this->_disableLayout()->_disableRender()->_redirect($returnTo);
    }
}