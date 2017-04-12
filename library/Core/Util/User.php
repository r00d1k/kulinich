<?php
/**
 * Core_Util_User.
 *
 * @class      Core_Util_User
 * @package    Core_Util
 * @subpackage Core_Util_User
 */
class Core_Util_User
{
    /**
     * Logs user in.
     *
     * @param string $email    Users's login.
     * @param string $password Users password, must be already encripted.
     *
     * @return boolean
     */
    static public function loginUser($email, $password)
    {
        $passwordEncrypted = self::passwordCrypt($password);

        $mapper = Auth_Model_Mapper_User::getInstance();
        $auth = new Zend_Auth_Adapter_DbTable();
        $auth
            ->setTableName($mapper->getStorage()->info(Zend_Db_Table::NAME))
            ->setIdentityColumn($mapper->map('email'))
            ->setCredentialColumn($mapper->map('password'))
            ->setIdentity($email)
            ->setCredential($passwordEncrypted)
            ->getDbSelect()
            ->join(
                array('uag' => 'user_acl_groups'),
                $mapper->getStorage()->info(Zend_Db_Table::NAME) . '.'.$mapper->map('id') . ' = uag.user_id',
                null
            )
            ->join(
                array('ag' => 'acl_groups'),
                'uag.group_id = ag.group_id',
                new Zend_Db_Expr('GROUP_CONCAT(ag.group_code) AS user_roles')
            )
            ->group($mapper->getStorage()->info(Zend_Db_Table::NAME) . '.'.$mapper->map('id'));

        $result = $auth->authenticate();

        if($result->isValid())
        {
            $dbData = $auth->getResultRowObject(
                array('user_id', 'user_email', 'user_roles')
            );
            $dbData->user_roles = explode(',', $dbData->user_roles);
            Zend_Auth::getInstance()->getStorage()->write($dbData);
            $mapper->find($dbData->user_id)->lastLogin = new Zend_Date();
            $session = new Zend_Session_Namespace('Zend_Auth');
            $session->setExpirationSeconds(12*3600);
            //$loginResult = true;
            $user = Auth_Model_Mapper_User::getInstance()->find($dbData->user_id);
            $user->lastLogin = new Zend_Date();
            $user->save();
        }
        return $result;
    }

    /**
     * Gets a user's remote Ip.
     *
     * @return integer
     */
    static public function getIp()
    {
        return ip2long($_SERVER['REMOTE_ADDR']);
    }

    /**
     * Encripting user's password.
     *
     * @param string $password Password to encript.
     *
     * @return string
     */
    static public function passwordCrypt($password)
    {
        return Zend_Crypt::hash('sha1', $password);
    }

    /**
     * Generates a random password.
     *
     * @return string
     */
    static public function generatePassword($number)
{
    $arr = array('a','b','c','d','e','f',
                 'g','h','i','j','k','l',
                 'm','n','o','p','r','s',
                 't','u','v','x','y','z',
                 'A','B','C','D','E','F',
                 'G','H','I','J','K','L',
                 'M','N','O','P','R','S',
                 'T','U','V','X','Y','Z',
                 '1','2','3','4','5','6',
                 '7','8','9','0',/*'.',',',
                 '(',')','[',']','!','?',
                 '&','^','%','@','*','$',
                 '<','>','/','|','+',*/'-',
                 '{','}'/*,'`','~'*/);
    $pass = "";
    for($i = 0; $i < $number; $i++)
    {
        $index = rand(0, count($arr) - 1);
        $pass .= $arr[$index];
    }
    return $pass;
}
    /**
     * Get id for current user.
     *
     * @static
     * @return integer
     */
    static public function getUserId()
    {
        $userId   = null;

        if(Zend_Auth::getInstance()->hasIdentity())
        {
            $identity = Zend_Auth::getInstance()->getIdentity();
            $userId   = $identity->user_id;
        }

        return $userId;
    }

    /**
     * Get role for current user.
     *
     * @static
     * @return string
     */
    static public function getUserRole()
    {
        $userRole = Core_Acl::ROLE_GUEST;

        if(Zend_Auth::getInstance()->hasIdentity())
        {
            $userRole = Core_Acl::ROLE_COMBINED;
        }

        return $userRole;
    }

    /**
     *  Checkeing is user has access.
     *
     * @param string $resource   Permission resource.
     * @param string $permission Permission privilege.
     *
     * @return boolean
     */
    static public function hasAccess($resource, $permission)
    {
        $userId   = self::getUserId();
        $userRole = self::getUserRole();
        $userAcl  = Core_Acl::getInstance($userId);
        return ($userAcl->has($resource) && $userAcl->isAllowed($userRole, $resource, $permission));
    }

    protected static $_authUSer = null;

    public static function setAuthUser($user)
    {
        self::$_authUSer = $user;
    }

    public static function getAuthUser()
    {
        return self::$_authUSer;
    }
}