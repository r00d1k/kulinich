<?php
class AccountController extends Core_Controller_Action_Abstract
{
    /**
     * Index Action.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_setLayout('system');
        $form = new Form_Account();
        $form->populate($this->_authUser->toArray());
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
        {
            $data = $form->getValues();
            unset($data['passwordConfirm']);
            if(empty($data['password']))
            {
                unset($data['password']);
            }
            else
            {
                $decodedPassword = $data['password'];
                $data['password'] = Core_Util_User::passwordCrypt($data['password']);
            }
            $this->_authUser->setFormData($data);
            $this->_authUser->save();
            $this
                ->_disableLayout()
                ->_disableRender()
                ->redirect(
                    $this->view->url(array(
                        'module'=>'default',
                        'controller'=>'account',
                        'action' => 'index'
                    ))
                );
            exit();
        }
        $this->view->form = $form;
    }
}