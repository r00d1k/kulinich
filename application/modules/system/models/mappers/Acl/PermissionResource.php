<?php
class System_Model_Mapper_Acl_PermissionResource extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'acl_permission_resources',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_key = 'id';

    protected $_map        = array(
        'id'           => 'id',
        'permissionId' => 'permission_id',
        'resourceId'   => 'resource_id',
        'permission'  => array(
            'type'      => self::RELATION_PARENT,
            'mapper'    => 'System_Model_Mapper_Acl_Permission',
            'fKey'      => 'permissionId',
            'load'      => true,
            'toArray'   => true,
        ),
        'resource'  => array(
            'type'      => self::RELATION_PARENT,
            'mapper'    => 'System_Model_Mapper_Acl_Resource',
            'fKey'      => 'resourceId',
            'load'      => true,
            'toArray'   => true,
        ),
    );
}