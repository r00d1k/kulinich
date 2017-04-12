<?php
/**
 * @author P.Matvienko
 * @file Template.php
 * @created 17.02.13
 */
class System_Model_Mapper_Email_Template_Locale extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'system_email_locales',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_key = 'id';

    protected $_map = array(
        'id'   => 'locale_id',
        'emailId'    => 'email_id',
        'languageId' => 'language_id',
        'fromName'   => 'locale_from_name',
        'subject'    => 'locale_subject',
        'textBody'   => 'locale_text_body',
        'htmlBody'   => 'locale_html_body',
        'language'   => array(
            'type'    => self::RELATION_PARENT,
            'mapper'  => 'System_Model_Mapper_Language',
            'fKey'    => 'languageId',
            'load'    => false,
            'toArray' => false,
        ),

    );
}