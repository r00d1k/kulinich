<?php

class System_Model_Mapper_Acl_Permission extends Core_Entity_Mapper_Abstract
{

    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'acl_permissions',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );
    protected $_key           = 'id';
    protected $_modelClass    = 'System_Model_Acl_Permission';
    protected $_map           = array(
        'id'        => 'permission_id',
        'code'      => 'permission_code',
        'parentId'  => 'parent_permission_id',
        'type'      => 'permission_type',
        'parent'    => array(
            self::MAP_TYPE        => self::RELATION_PARENT,
            self::MAP_MAPPER      => 'System_Model_Mapper_Acl_Permission',
            self::MAP_FOREIGN_KEY => 'parentId',
            self::MAP_LOAD        => false
        ),
        'children'  => array(
            self::MAP_TYPE        => self::RELATION_CHILDREN,
            self::MAP_MAPPER      => 'System_Model_Mapper_Acl_Permission',
            self::MAP_FOREIGN_KEY => 'parentId'
        ),
        'resources' => array(
            self::MAP_TYPE          => self::RELATION_M2M,
            self::MAP_MIDDLE_MAPPER => 'System_Model_Mapper_Acl_PermissionResource',
            self::MAP_MAPPER        => 'System_Model_Mapper_Acl_Resource',
            self::MAP_FOREIGN_KEY   => 'permissionId',
        ),
        'groups'    => array(
            self::MAP_TYPE          => self::RELATION_M2M,
            self::MAP_MIDDLE_MAPPER => 'System_Model_Mapper_Acl_GroupPermission',
            self::MAP_MAPPER        => 'System_Model_Mapper_Acl_Group',
            self::MAP_FOREIGN_KEY   => 'permissionId',
        ),
    );

}

