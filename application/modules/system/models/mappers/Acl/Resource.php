<?php
class System_Model_Mapper_Acl_Resource extends Core_Entity_Mapper_Abstract
{

    const TYPE_MVC = 'mvc';
    const TYPE_CUSTOM = 'custom';

    protected $_storageConfig = array(
        Core_Entity_Storage_DbSelect_Abstract::NAME     => 'acl_resources',
        Core_Entity_Storage_DbSelect_Abstract::SEQUENCE => true,
    );

    protected $_key = 'id';

    protected $_map        = array(
        'id'     => 'resource_id',
        'type'   => 'resource_type',
        'code'   => 'resource_code',
        'action' => 'resource_action',
        'permissions'    => array(
            self::MAP_TYPE             => self::RELATION_M2M,
            self::MAP_MIDDLE_MAPPER    => 'System_Model_Mapper_Acl_PermissionResource',
            self::MAP_MAPPER           => 'System_Model_Mapper_Acl_Permission',
            self::MAP_FOREIGN_KEY      => 'resourceId',
        ),
    );

    public static function syncCustomResources()
    {
        if(!is_file(CONFIG_PATH . '/custom.permissions.yml'))
        {
            return false;
        }
        $resources = new Zend_Config_Yaml(CONFIG_PATH . '/custom.permissions.yml', 'resources');
        $resources = $resources->toArray();

        $allResources = array();
        foreach(System_Model_Mapper_Acl_Resource::getInstance()->findAll(array('type' => 'custom')) as $resource)
        {
            $allResources[$resource->code . '::' . $resource->action] = $resource;
        }
        foreach($resources as $opt)
        {
            if(!isset($allResources[$opt['code'] . '::' . $opt['action']]))
            {
                $res = System_Model_Mapper_Acl_Resource::getInstance()->getModel();
                $res->code = $opt['code'];
                $res->action = $opt['action'];
                $res->type = 'custom';
                $res->save();
            }
            else
            {
                unset($allResources[$opt['code'] . '::' . $opt['action']]);
            }
        }
        if(!empty($allResources))
        {
            foreach($allResources as $item)
            {
                $item->delete();
            }
        }
    }

    public static function syncMvcResources()
    {
        $allResources = array();
        foreach(System_Model_Mapper_Acl_Resource::getInstance()->findAll(array('type' => 'mvc')) as $resource)
        {
            $allResources[$resource->code . '::' . $resource->action] = $resource;
        }

        foreach(Zend_Controller_Front::getInstance()->getControllerDirectory() as $module => $controllersPath)
        {
            if(!is_dir($controllersPath)) continue;
            $dir = opendir($controllersPath);
            while($item = readdir($dir))
            {

                if(stristr($item, 'Controller'))
                {
                    $contents = file_get_contents($controllersPath . DIRECTORY_SEPARATOR . $item);
                    $hasController = preg_match('/class +([a-zA-z0-9_]+Controller) +extends +(Proj|Core)_Controller/', $contents, $matches);
                    if(!$hasController) continue;
                    $controllerClass = $matches[1];
                    $controllerName = strtolower(preg_replace('/([^.])([A-Z]{1,1})/', '$1-$2', substr($item, 0, -14)));

                    Zend_Loader::loadFile($controllersPath . DIRECTORY_SEPARATOR . $item, null, true);
                    foreach(get_class_methods($controllerClass) as $methodName)
                    {
                        if(strlen($methodName) > 6 && strtolower(substr($methodName, -6)) == 'action')
                        {
                            $methodName = strtolower(preg_replace('/([^.])([A-Z]{1,1})/', '$1-$2', substr($methodName, 0, -6)));
                            $itemMarker = $module.'::'.$controllerName.'::'.$methodName;
                            if(empty($allResources[$itemMarker]))
                            {
                                $resource             = System_Model_Mapper_Acl_Resource::getInstance()->getModel();
                                $resource->type = System_Model_Mapper_Acl_Resource::TYPE_MVC;
                                $resource->code = $module . '::' . $controllerName;
                                $resource->action = $methodName;
                                $resource->save();
                            }
                            else
                            {
                                unset($allResources[$itemMarker]);
                            }
                        }
                    }
                }
            }
            closedir($dir);
        }
        foreach($allResources as $resource)
        {
            $resource->delete();
        }
    }
}