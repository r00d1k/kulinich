<?php
class System_Model_Mapper_User extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'users',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_modelClass = 'System_Model_User';
    protected $_key = 'id';

    protected $_map        = array(
        'id'            => 'user_id',
        'firstName'     => 'user_first_name',
        'lastName'      => 'user_last_name',
        'email'         => 'user_email',
        'password'      => 'user_password',
        'lastLogin'     => array(
            self::MAP_FIELD     => 'user_last_login_time',
            self::MAP_CONVERTER => 'Datetime'
        ),
        'lastActivity'  => array(
            self::MAP_FIELD     => 'user_last_activity_time',
            self::MAP_CONVERTER => 'Datetime'
        ),
        'lastIp'        => array(
            self::MAP_FIELD     => 'user_last_ip',
            self::MAP_CONVERTER => 'Ip'
        ),
        'receiveNotify' => 'user_enable_notifications',
        'language' => 'user_system_language',
        'groups'        => array(
            self::MAP_TYPE             => self::RELATION_M2M,
            self::MAP_MIDDLE_MAPPER    => 'System_Model_Mapper_UserAclGroup',
            self::MAP_MAPPER           => 'System_Model_Mapper_Acl_Group',
            self::MAP_FOREIGN_KEY      => 'userId',
        ),
    );
}