<?php
class System_AclGroupsController extends Core_Controller_Action_Scaffold
{
    protected $_mapper = 'System_Model_Mapper_Acl_Group';
    protected $_captions = array(
        'index' => 'navigation:system-acl-groups',
        'create' => 'navigation:system-acl-groups-create',
        'edit'   => 'navigation:system-acl-groups-edit'
    );
}