<?php
class System_Model_Mapper_Team extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'team',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_modelClass = 'System_Model_Team';

    protected $_key = 'id';

    protected $_map = array(
        'id'            => 'team_id',
        'description'   => 'team_description',
        'name'          => 'team_title',
        'image'         => 'team_image',
        'rank'          => 'team_rank',
        'specification' => 'team_specification',
        'useBigPhoto'   => 'team_use_big_photo'
    );
}