<?php
class Auth_Model_Mapper_User extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'users',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

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
        )
    );
}