<?php
/**
 * @author P.M.
 */
class System_Model_Mapper_Static_Page_Locale extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'static_page_locales',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_key = 'id';

    protected $_map = array(
        'id'         => 'locale_id',
        'pageId'     => 'page_id',
        'languageId' => 'language_id',
        'title'      => 'html_title',
        'keywords'   => 'html_keywords',
        'content'    => 'locale_content',
        'page'       => array(
            'type'    => self::RELATION_PARENT,
            'mapper'  => 'System_Model_Mapper_Static_Page',
            'fKey'    => 'pageId',
            'load'    => true,
            'toArray' => true,
        ),
        'language'   => array(
            'type'    => self::RELATION_PARENT,
            'mapper'  => 'System_Model_Mapper_Language',
            'fKey'    => 'languageId',
            'load'    => false,
            'toArray' => false,
        ),
    );
}