<?php
class System_Model_Mapper_Clinic_Case extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'clinic_cases',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_key = 'id';

    protected $_map = array(
        'id'      => 'case_id',
        'code'    => 'case_code',
        'title'   => 'case_title',
        'isEnabled'=> 'is_enabled',
        'rank' => 'case_rank',
        'photos' => array(
            self::MAP_TYPE        => self::RELATION_CHILDREN,
            self::MAP_MAPPER      => 'System_Model_Mapper_Clinic_Photo',
            self::MAP_TO_ARRAY    => true,
            self::MAP_FOREIGN_KEY => 'caseId',
            self::MAP_KEY         => 'id',
        ),
        'cover' => array(
            self::MAP_TYPE        => self::RELATION_CHILD,
            self::MAP_MAPPER      => 'System_Model_Mapper_Clinic_Photo',
            self::MAP_FOREIGN_KEY => 'caseId',
            self::MAP_KEY         => 'id',
            self::MAP_FILTER      => array('isCover' => 'yes'),
            self::MAP_SAVE        => false,
            self::MAP_LOAD        => false
        )
    );
}