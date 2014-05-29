<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Controller_Plugin_Navigation
 * @subpackage Controller_Plugin
 * @use Zend_Controller_Plugin_Abstract
 *
 * Navigation
 */
class Core_Controller_Plugin_Navigation extends Zend_Controller_Plugin_Abstract
{
    /**
     * Post Dispatcher.
     *
     * @param Zend_Controller_Request_Abstract $request Http request instance.
     *
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $userId     = Core_Util_User::getUserId();
        $acl        = Core_Acl::getInstance($userId);
        $nav = new Zend_Config_Yaml(
            CONFIG_PATH . '/navigation.yml', 'navigation'
        );
        $nav = new Zend_Navigation($nav);
        $this->getView()->navigation($nav)->setAcl($acl)->setRole(Core_Acl::ROLE_COMBINED);
    }

    /**
     * Get View object.
     *
     * @return Zend_View
     */
    public function getView()
    {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        if($bootstrap->hasResource('view'))
        {
            return $bootstrap->getResource('view');
        }
        else
        {
            require_once 'Zend/Controller/Action/HelperBroker.php';
            /* @var $viewRenderer Zend_Controller_Action_Helper_ViewRenderer */
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
            $viewRenderer->initView();
            return $viewRenderer->view;
        }
    }
}