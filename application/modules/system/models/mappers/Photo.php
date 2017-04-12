<?php
class System_Model_Mapper_Photo extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'photos',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_modelClass = 'System_Model_Photo';

    protected $_key = 'id';

    protected $_map = array(
        'id'          => 'photo_id',
        'description' => 'photo_description',
        'title'       => 'photo_title',
        'image'       => 'photo_image',
        'rank'        => 'photo_rank',
        'gallery'     => 'photo_gallery'
    );
}