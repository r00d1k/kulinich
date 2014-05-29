<?php
class System_Model_Mapper_Translation extends Core_Entity_Mapper_Abstract
{
    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'translations',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_key = 'id';

    protected $_map = array(
        'id'         => 'translation_id',
        'section'    => 'translation_module',
        'key'        => 'translation_key',
        'languageId' => 'translation_locale',
        'value'      => 'translation_value',
        'language'   => array(
            'type'    => self::RELATION_PARENT,
            'mapper'  => 'System_Model_Mapper_Language',
            'fKey'    => 'languageId',
            'load'    => false,
            'toArray' => false,
        ),
    );

    public function getGrid()
    {
        $base = $this->getStorage()->getBaseQuery();
        $base->group(
            array(
                 $this->map('section'),
                 $this->map('key'),
            )
        );
        $base->reset(Zend_Db_Select::COLUMNS);
        $fields = $this->getFields();
        $fields['languages'] = new Zend_Db_Expr('GROUP_CONCAT('.$fields['languageId'].' SEPARATOR \' \')');
        $base->columns($fields);
        $config = $this->getConfig();
        return new Core_Grid_Html($base, $config['grid']);
    }
}