<?php
class System_Model_Mapper_Language extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'languages',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => false,
    );

    protected $_key = 'id';

    protected $_map        = array(
        'id'     => 'language_id',
        'name'   => 'language_name',
        'isAvailable'   => 'language_is_avaliable',
        'locale'   => 'language_locale',
    );
}