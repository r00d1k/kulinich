<?php
class System_Model_Mapper_Acl_Group extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'acl_groups',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_key = 'id';
    protected $_modelClass = 'System_Model_Acl_Group';

    protected $_map        = array(
        'id'       => 'group_id',
        'name'     => 'group_name',
        'code'     => 'group_code',
        'isGuest'  => 'group_is_guest',
        'users'    => array(
            self::MAP_TYPE             => self::RELATION_M2M,
            self::MAP_MIDDLE_MAPPER    => 'System_Model_Mapper_UserAclGroup',
            self::MAP_MAPPER           => 'System_Model_Mapper_User',
            self::MAP_FOREIGN_KEY      => 'groupId',
        ),
        'permissions'    => array(
            self::MAP_TYPE             => self::RELATION_M2M,
            self::MAP_MIDDLE_MAPPER    => 'System_Model_Mapper_Acl_GroupPermission',
            self::MAP_MAPPER           => 'System_Model_Mapper_Acl_Permission',
            self::MAP_FOREIGN_KEY      => 'groupId',
        ),
    );
}