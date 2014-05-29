<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Controller_Action_Abstract
 * @subpackage Controller
 * @use Zend_Controller_Action
 *
 * Action Controller
 */
abstract class Core_Controller_Action_Abstract extends Zend_Controller_Action
{
    /**
     * @var Model_User Logged in user
     */
    protected $_authUser = null;

    /**
     * @var Bootstrap
     */
    protected $_bootstrap = null;

    /**
     * @var boolean
     */
    protected $_isAjax = false;
    protected $_isJson = false;

    protected $_layout = null;


    /**
     *  Initialization.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->_setLayout('default');
        $this->view->pageCode = $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName();
        if($this->getRequest()->getModuleName() != 'default')
        {
            $this->_setLayout('system');
        }
        $this->_bootstrap = Zend_Registry::get('bootstrap');
        if($this->getRequest()->isXmlHttpRequest())
        {
            $this->_helper->layout->setLayout('ajax');
            $this->_isAjax = $this->view->isAjax = true;
            $accept = $this->getRequest()->getServer();
            $accept = $accept['HTTP_ACCEPT'];
            if(stristr($accept, 'application/json') || stristr($accept, 'text/javascript'))
            {
                $this->_isJson = $this->view->isJson = true;
                $this->_helper->layout->setLayout('json');
            }
        }

        $this->view->language = $this->getRequest()->getParam('language');

        if(Zend_Auth::getInstance()->hasIdentity())
        {
            $identity      = Zend_Auth::getInstance()->getIdentity();
            $mapper = Auth_Model_Mapper_User::getInstance();
            $user = $mapper->find($identity->{$mapper->map($mapper->getKeyField())});
            if($user->getKey() != null)
            {
                $user->lastActivity = date('Y-m-d H:i:s');
                $user->lastIp       = Core_Util_User::getIp();
                $user->save();
                $this->view->authUser = $this->_authUser = $user;
                Core_Util_User::setAuthUser($user);
            }
        }
    }

    /**
     * Disable Layout.
     *
     * @return Core_Controller_Action_Abstract
     */
    protected function _disableLayout()
    {
        if($this->_helper->hasHelper('Layout'))
        {
            $this->_helper->layout->disableLayout();
        }
        return $this;
    }


    /**
     * Set Layout.
     *
     * @param string $layout Layout name.
     *
     * @return Core_Controller_Action_Abstract
     */
    protected function _setLayout($layout)
    {
        if($this->_helper->hasHelper('Layout'))
        {
            $this->_helper->layout->setLayout($layout);
        }
        return $this;
    }


    /**
     * Disable Renderer.
     *
     * @return Core_Controller_Action_Abstract
     */
    protected function _disableRender()
    {
        if($this->_helper->hasHelper('viewRenderer'))
        {
            $this->_helper->viewRenderer->setNoRender(true);
        }
        return $this;
    }

    protected function _actionSuccess($returnTo, $dialogParams=array())
    {
        if(!$this->_isJson)
        {
            $return = $this->getRequest()->getParam('return-to', null);
            if(empty($return))
            {
                $return = $returnTo;
            }
            else
            {
                $return = base64_decode($return);
            }
            $this->redirect($return);
            exit();
        }
        else
        {
            echo $this->_helper->json($dialogParams);
        }
    }

    protected function _pickUser()
    {
        $term = $this->getRequest()->getParam('term', null);
        $id = $this->getRequest()->getParam('id', null);

        if($id != null)
        {
            $user = System_Model_Mapper_User::getInstance()->find($id);
            $out = null;
            if($user != null)
            {
                $out = $user->firstName . ' ' . $user->lastName . '(' . $user->email . ')';
            }
            echo $out;
        }
        elseif($term != null)
        {
            $req = System_Model_Mapper_User::getInstance()->getDataRequest();
            $req
                ->where('firstName', '%'.$term.'%', 'LIKE')
                ->orWhere('lastName', '%'.$term.'%', 'LIKE')
                ->orWhere('email', '%'.$term.'%', 'LIKE');

            $req->limitPerPage(40);
            $out = array();
            foreach($req->getPage(1) as $user)
            {
                $out[] = array(
                    'id' => $user->id,
                    'value' => $user->firstName . ' ' . $user->lastName . '(' . $user->email . ')',
                );
            }
            echo  json_encode($out);
        }

        $this->_disableLayout()->_disableRender();
    }
}