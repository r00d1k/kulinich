<?php
class System_Model_Mapper_Static_Page extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'static_pages',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );
    protected $_modelClass = 'System_Model_Static_Page';
    protected $_key = 'id';

    protected $_map = array(
        'id'      => 'page_id',
        'code'    => 'page_code',
        'background' => 'page_background',
        'locales' => array(
            self::MAP_TYPE        => self::RELATION_CHILDREN,
            self::MAP_MAPPER      => 'System_Model_Mapper_Static_Page_Locale',
            self::MAP_TO_ARRAY    => true,
            self::MAP_FOREIGN_KEY => 'pageId',
            self::MAP_KEY         => 'id',
            self::MAP_ASSOC_KEY   => 'languageId',
        ),
        'locale'  => array(
            self::MAP_TYPE         => self::RELATION_LOCALE,
            self::MAP_MAPPER       => 'System_Model_Mapper_Static_Page_Locale',
            self::MAP_FOREIGN_KEY  => 'pageId',
            self::MAP_KEY          => 'id',
            self::MAP_LANGUAGE_KEY => 'languageId',
            self::MAP_SAVE         => false,
            self::MAP_LOAD         => true
        )
    );
}