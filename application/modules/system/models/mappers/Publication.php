<?php
class System_Model_Mapper_Publication extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'publications',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_key = 'id';
    protected $_modelClass = 'System_Model_Publication';

    protected $_map = array(
        'id'        => 'publication_id',
        'title'     => 'publication_title',
        'code'      => 'publication_code',
        'content'   => 'publication_content',
        'image'     => 'publication_image',
        'isEnabled' => 'publication_is_enabled',
    );
}