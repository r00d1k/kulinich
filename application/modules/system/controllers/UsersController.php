<?php
class System_UsersController extends Core_Controller_Action_Scaffold
{
    protected $_mapper = 'System_Model_Mapper_User';
    protected $_captions = array(
        'index' => 'navigation:system-users',
        'create' => 'navigation:system-users-create',
        'edit'   => 'navigation:system-users-edit'
    );

    public function createAction()
    {
        $user = System_Model_Mapper_User::getInstance()->getModel();
        $form = $user->getForm();
        $form->getMainForm()->getElement('password')->setRequired(true);
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
        {
            $data = $form->getValues();
            $password = $data['password'];
            if($data['password'] == '')
            {
                unset($data['password']);
            }
            else
            {

                $data['password'] = Core_Util_User::passwordCrypt($data['password']);
            }
            $user->setFormData($data);
            $user->save();
            $data['password'] = $password;

            /** @var System_Model_Email_Template$notify  */
            $notify = System_Model_Mapper_Email_Template::getInstance()->find(
                array(
                     'code' => 'account-created'
                )
            );
            /** @var Zend_Mail $notify  */
            $notify = $notify->getMail($user->email, $user->language, $data);

            $notify->send(Core_Mailer_Manager::getInstance()->getAdapter());
            $this->_actionSuccess(
                $this->view->url(array('action' => 'index')),
                array(
                     'success' => true,
                     'reload'  => true,
                )
            );
        }

        $this->view->form = $form;
        $this->view->user = $user;
    }

    public function editAction()
    {
        $user = $this->getRequest()->getParam('id',null);
        $user = System_Model_Mapper_User::getInstance()->find($user);
        if($user == null)
        {
            throw new Zend_Controller_Action_Exception('User not found');
        }
        $form = $user->getForm();
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
        {
            $data = $form->getValues();
            if($data['password'] == '')
            {
                unset($data['password']);
            }
            else
            {
                $decodedPassword = $data['password'];
                $data['password'] = Core_Util_User::passwordCrypt($data['password']);
            }
            $user->setFormData($data);
            $user->save();
            if(!empty($decodedPassword))
            {
                $notify = System_Model_Mapper_Email_Template::getInstance()->find(
                    array(
                         'code' => 'account-password-changed-by-admin'
                    )
                );
                if($notify != null)
                {
                    $data['password'] = $decodedPassword;
                    $notify = $notify->getMail($user->email, $user->language, $data);
                    //Zend_Debug::dump(Core_Mailer_Manager::getInstance()->getAdapter());exit();
                    $notify->send(Core_Mailer_Manager::getInstance()->getAdapter());
                }
            }
            $this->_actionSuccess(
                $this->view->url(array('action' => 'index')),
                array(
                     'success' => true,
                     'reload'  => true,
                )
            );
        }

        $this->view->form = $form;
        $this->view->user = $user;
    }
}