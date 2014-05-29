<?php
class System_AclResourcesController extends Core_Controller_Action_Abstract
{
    protected $_mapper = 'System_Model_Mapper_Acl_Resource';
    protected $_captions = array(
        'index' => 'navigation:system-acl-resources',
        'create' => 'navigation:system-acl-resources-create',
        'edit'   => 'navigation:system-acl-resources-edit'
    );

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
        if($this->_mapper != null && is_string($this->_mapper))
        {
            $mapper = $this->_mapper;
            $this->_mapper = $mapper::getInstance();
        }
    }

    public function indexAction()
    {
        System_Model_Mapper_Acl_Resource::syncCustomResources();
        System_Model_Mapper_Acl_Resource::syncMvcResources();
        $this->view->grid = $this->_mapper->getGrid();
        $this->view->caption = !empty($this->_captions['index']) ? $this->_captions['index'] : 'list';
    }


}