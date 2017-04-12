<?php
class System_Model_Mapper_UserAclGroup extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'user_acl_groups',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_key = 'id';

    protected $_map        = array(
        'id'      => 'id',
        'userId'  => 'user_id',
        'groupId' => 'group_id',
        'user'  => array(
            'type'      => self::RELATION_PARENT,
            'mapper'    => 'System_Model_Mapper_User',
            'fKey'      => 'userId',
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