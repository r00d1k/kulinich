<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Controller_Plugin_Auth
 * @subpackage Controller_Plugin
 * @use Zend_Controller_Plugin_Abstract
 *
 * Auth Plugin
 */
class Core_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var array Location for users whom requires to login.
     */
    protected $_authLocation = array(
        'module'     => 'auth',
        'controller' => 'index',
        'action'     => 'index'
    );

    protected $_deniedLocation = array(
        'module'     => 'auth',
        'controller' => 'denied',
        'action'     => 'index'
    );

    protected $_allowedPages = array(
        'default::error:error',
        'auth::logout:index'
    );

    /**
     * Pre Dispatcher.
     *
     * @param Zend_Controller_Request_Abstract $request Http request instance.
     *
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $userId   = Core_Util_User::getUserId();
        $userAcl  = Core_Acl::getInstance($userId);
        $resource   = $request->getModuleName() . '::' . $request->getControllerName();
        $permission = $request->getActionName();

        $aPath = $resource . ':' . $permission;
        if(!in_array($aPath, $this->_allowedPages))
        {

            if(!$userAcl->has($resource) || !$userAcl->isAllowed(Core_Acl::ROLE_COMBINED, $resource, $permission))
            {
                if($userId == null)
                {
                    $request
                        ->setModuleName($this->_authLocation['module'])
                        ->setControllerName($this->_authLocation['controller'])
                        ->setActionName($this->_authLocation['action']);
                }
                else//if(1)
                {
                    $request
                        ->setModuleName($this->_deniedLocation['module'])
                        ->setControllerName($this->_deniedLocation['controller'])
                        ->setActionName($this->_deniedLocation['action']);
                }
            }
        }


        /*$userId   = Core_Util_User::getUserId();
        $userRole = Core_Util_User::getUserRole();
        $userAcl  = Core_Acl::getInstance($userId);

        $resource   = $request->getModuleName() . '::' . $request->getControllerName();
        $permission = $request->getActionName();
        if(!('auth::denied' == $resource and 'index' == $permission))
        {
            if(!$userAcl->has($resource))
            {
                if('default::index' == $resource and 'index' == $permission)
                {
                    $this->_goToLogin($request);
                }
                else
                {
                    $this->_goToDenied($request);
                }
            }
            elseif(!$userAcl->isAllowed($userRole, $resource, $permission))
            {
                if(Core_Acl::ROLE_GUEST == $userRole)
                {
                    $this->_goToLogin($request);
                }
                else
                {
                    $this->_goToDenied($request);
                }
            }
        }*/
    }
}