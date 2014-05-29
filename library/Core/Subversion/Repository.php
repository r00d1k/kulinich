<?php
class Core_Subversion_Repository
{
    const ACCESS_READ = 'read';
    const ACCESS_WRITE = 'write';
    const ACCESS_NONE = 'none';

    protected $_path = null;
    protected $_options = array();

    protected function __construct($repository)
    {
        $this->_path = self::getPath() . $repository;
        /** @var Bootstrap $bootstrap  */
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $options = $bootstrap->getOption('svn');
        $this->_options = $options['repository'];
    }

    public function setSvnConfig($repositoryTitle, $guestAccess = self::ACCESS_NONE)
    {
        $configPath = $this->_path . '/' . $this->_options['repoConfig'];
        $config = "[general]\r\n";
        $config .= "anon-access = " . ($guestAccess != '' ? $guestAccess : Svn_Model_Mapper_Repository::ACCESS_NONE) . "\r\n";
        $config .= "password-db = passwd\r\n";
        $config .= "realm = " . (!empty($repositoryTitle) ? $repositoryTitle : 'Sandbox Repository') ."\r\n";
        $config .= "[sasl]\r\n";
        $file = fopen($configPath, 'w');
        fwrite($file, $config);
        fclose($file);
    }

    public function resetUsers()
    {
        $configPath = $this->_path . '/' . $this->_options['usersFile'];
        $file = fopen($configPath, 'w');
        fwrite($file, "[users]");
        fclose($file);
    }

    public function addUser($username, $token)
    {
        $configPath = $this->_path . '/' . $this->_options['usersFile'];
        $file = fopen($configPath, 'a');
        fwrite($file, "\n".$username . ' = ' . $token);
        fclose($file);
    }

    public static function getPath()
    {
        /** @var Bootstrap $bootstrap  */
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $options = $bootstrap->getOption('svn');
        $path = $options['repository']['path'];
        if(substr($path, -1) != '/')
        {
            $path .= '/';
        }
        return $path;
    }

    public static function getRepository($repository)
    {
        $path = self::getPath() . $repository;
        if(!is_dir($path))
        {
            @passthru('svnadmin create ' . $path);
            $repo = new Core_Subversion_Repository($repository);
            $repo->setSvnConfig('Sandbox Repository', self::ACCESS_NONE);
        }
        return new Core_Subversion_Repository($repository);
    }
}