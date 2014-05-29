<?php
class System_Model_Mapper_Acl_GroupPermission extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'acl_group_permissions',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_key = 'id';

    protected $_map        = array(
        'id'           => 'id',
        'permissionId' => 'permission_id',
        'groupId'      => 'group_id',
        'permission'  => array(
            'type'      => self::RELATION_PARENT,
            'mapper'    => 'System_Model_Mapper_Acl_Permission',
            'fKey'      => 'permissionId',
            'load'      => true,
            'toArray'   => true,
        ),
        'group'  => array(
            'type'      => self::RELATION_PARENT,
            'mapper'    => 'System_Model_Mapper_Acl_Group',
            'fKey'      => 'groupId',
            'load'      => true,
            'toArray'   => true,
        ),
    );
}