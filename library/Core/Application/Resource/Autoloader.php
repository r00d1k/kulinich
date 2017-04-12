<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Application_Resource_Autoloader
 * @subpackage Resource
 *
 * Autoloader
 */
class Core_Application_Resource_Autoloader extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Defined by Zend_Application_Resource_Resource.
     *
     * @return void
     */
    public function init()
    {
        $options = $this->getOptions();
        if(!empty($options))
        {
            require_once 'Zend/Loader/Autoloader.php';
            $autoloader = Zend_Loader_Autoloader::getInstance();

            if(isset($options['resources']))
            {
                foreach($options['resources'] as $module => $resConf)
                {
                    $resource = new Zend_Loader_Autoloader_Resource($resConf);
                    if('default' == $module)
                    {
                        $autoloader->pushAutoloader($resource);
                    }
                    else
                    {
                        $autoloader->pushAutoloader($resource, $module);
                    }
                }
            }
            if(isset($options['resourceTypes']) && is_array($options['resourceTypes']))
            {
                foreach(Zend_Loader_Autoloader::getInstance()->getAutoloaders() as $autoloader)
                {
                    $autoloader->addResourceTypes($options['resourceTypes']);
                }
            }
        }
    }
}
