<?php
class Auth_IndexController extends Core_Controller_Action_Abstract
{
    /**
     *  Logs user in.
     *
     * @return boolean
     */
    public function indexAction()
    {
        $form = new Auth_Form_Login();
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
        {
            $data = $form->getValues();
            $loggedIn = Core_Util_User::loginUser(
                $data['email'],
                $data['password']
            );
            if($loggedIn->isValid())
            {
                return $this->_disableLayout()->_disableRender()->redirect($_SERVER['REQUEST_URI']);
            }
            else
            {
                $this->view->errors = $loggedIn->getMessages();
            }
        }

        $this->view->form = $form;
    }
}