<?php
/**
 * @author P.Matvienko
 * @file Template.php
 * @created 17.02.13
 */
class System_Model_Mapper_Email_Template extends Core_Entity_Mapper_Abstract
{
    const CONTENT_TEXT = 'text';
    const CONTENT_HTML = 'html';

    protected $_modelClass = 'System_Model_Email_Template';

    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'system_emails',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_key = 'id';

    protected $_map = array(
        'id'         => 'email_id',
        'code'       => 'email_code',
        'from'       => 'email_from',
        'variables'  => 'email_variables',
        'contentType' => 'email_content_type',
        'locales'  => array(
            'type'     => self::RELATION_CHILDREN,
            'mapper'      => 'System_Model_Mapper_Email_Template_Locale',
            'toArray'     => true,
            'fKey'        => 'emailId',
            'key'         => 'id',
            'assocKey'    => 'languageId',
        ),
        'locale'   => array(
            'type'        => self::RELATION_LOCALE,
            'mapper'      => 'System_Model_Mapper_Email_Template_Locale',
            'fKey'        => 'emailId',
            'key'         => 'id',
            'languageKey' => 'languageId',
            self::MAP_SAVE=> false,
        )
    );
}