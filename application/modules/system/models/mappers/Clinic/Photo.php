<?php
class System_Model_Mapper_Clinic_Photo extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'clinic_case_photos',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_modelClass = 'System_Model_Clinic_Photo';

    protected $_key = 'id';

    protected $_map = array(
        'id'          => 'photo_id',
        'caseId'      => 'case_id',
        'description' => 'photo_description',
        'image'       => 'photo_image',
        'rank'        => 'photo_rank',
        'isCover'     => 'photo_is_cover',
        'case'       => array(
            'type'    => self::RELATION_PARENT,
            'mapper'  => 'System_Model_Mapper_Clinic_Case',
            'fKey'    => 'caseId',
        ),
    );
}