<?php
date_default_timezone_set('GMT');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('DEFAULT_LANGUAGE', 'ru');
define('LOG_POSTFIX', date('Y-m-d'));

define('PUBLIC_PATH', dirname(__FILE__));
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('APPLICATION_PATH', ROOT_PATH . '/application');
define('MODULE_PATH', APPLICATION_PATH . '/modules');
define('CONFIG_PATH', APPLICATION_PATH . '/configs');
define('LIB_PATH', ROOT_PATH . '/library');

set_include_path(
    LIB_PATH . PATH_SEPARATOR .
    get_include_path()
);

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

/** Load application configuration file **/
/*
$frontendOptions = array(
    'master_files' => array(
        CONFIG_PATH . '/application.yml',
        CONFIG_PATH . '/cache.yml',
        CONFIG_PATH . '/route.yml',
        CONFIG_PATH . '/log.yml',
        CONFIG_PATH . '/database.yml'
    ),
    'lifetime' => '2',
    'automatic_serialization' => '1'
);

$backendOptions = array(
    'cache_dir' => ROOT_PATH . '/data/cache/config/'
);

$appConfigCache = Zend_Cache::factory('File', 'File', $frontendOptions, $backendOptions);

$config = $appConfigCache->load(md5(CONFIG_PATH . '/application.yml'));*/

if(1)//false === $config)
{
    $config = new Zend_Config_Yaml(
        CONFIG_PATH . '/cache.yml',
        APPLICATION_ENV,
        array('allowModifications'=>true)
    );

    $routeConfig = new Zend_Config_Yaml(
        CONFIG_PATH . '/route.yml',
        APPLICATION_ENV
    );

    $logConfig = new Zend_Config_Yaml(
        CONFIG_PATH . '/log.yml',
        APPLICATION_ENV
    );

    $databaseConfig = new Zend_Config_Yaml(
        CONFIG_PATH . '/database.yml',
        APPLICATION_ENV
    );
    $appConfig = new Zend_Config_Yaml(
        CONFIG_PATH . '/application.yml',
        APPLICATION_ENV
    );

    $config->merge($logConfig);
    $config->merge($databaseConfig);
    $config->merge($appConfig);
    $config->merge($routeConfig);

    //$appConfigCache->save($config, md5(CONFIG_PATH . '/application.yml'));
}


$application = new Zend_Application(
    APPLICATION_ENV,
    $config
);
$application->bootstrap();
Zend_Registry::getInstance()->set('bootstrap', $application->getBootstrap());

$application->run();