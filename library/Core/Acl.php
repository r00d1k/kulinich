<?php
class Core_Acl extends Zend_Acl
{
    const ROLE_GUEST    = 'core-acl-guest';
    const ROLE_MEMBER   = 'core-acl-member';
    const ROLE_COMBINED = 'core-acl-combined';

    public function __construct($userId = null)
    {
        $this->_init($userId);
    }

    public static function hasAccessTo($resource = null, $privilege = null)
    {
        return Core_Acl::getInstance()->isAllowed(Core_Acl::ROLE_COMBINED, $resource, $privilege);
    }

    /**
     * Returns true if and only if the Role has access to the Resource.
     *
     * @param Zend_Acl_Role_Interface|string     $role      User role.
     * @param Zend_Acl_Resource_Interface|string $resource  Resource name.
     * @param string                             $privilege Resource privilege.
     *
     * @uses Zend_Acl::get()
     * @uses Zend_Acl_Role_Registry::get()
     * @return boolean
     */
    public function isAllowed($role = null, $resource = null, $privilege = null)
    {
        $result = false;

        if($this->has($resource))
        {
            $result = parent::isAllowed($role, $resource, $privilege);
        }

        return $result;
    }

    protected function _init($userId = null)
    {
        $this->deny();
        if(!empty($userId))
        {
            $groups = $this->_getDbAdapter()
                ->select()
                ->from(
                    array('g' => 'acl_groups'),
                    'group_code'
                )
                ->joinLeft(
                    array('ug' => 'user_acl_groups'),
                    'g.group_id = ug.group_id',
                    null
                )
                ->where('ug.user_id = ?', $userId)
                ->orWhere('g.group_is_guest = ?', 'yes')
                ->query()
                ->fetchAll(Zend_Db::FETCH_COLUMN);
        }
        else
        {
            $groups = $this->_getDbAdapter()
                ->select()
                ->from(
                    array('g' => 'acl_groups'),
                    'group_code'
                )
                ->orWhere('g.group_is_guest = ?', 'yes')
                ->query()
                ->fetchAll(Zend_Db::FETCH_COLUMN);
        }

        if(!empty($groups))
        {
            foreach($groups as $group)
            {
                $this->addRole(new Zend_Acl_Role($group));
                $this->_initAclByRole($group);
            }
            $this->addRole(new Zend_Acl_Role(self::ROLE_COMBINED), $groups);
        }
    }

    protected function _initAclByRole($role)
    {
        $resources = $this->_getResourcesByRole($role);
        if(!empty($resources))
        {
            foreach($resources as $resource)
            {
                $resourceParts = explode("::", $resource['resource_code']);
                if(!$this->has($resourceParts[0]))
                {
                    $this->addResource($resourceParts[0]);
                    $this->allow($role, $resourceParts[0], 'section');
                }
                $resourceParts = explode("-", $resource['resource_code']);
                if(!$this->has($resourceParts[0]))
                {
                    $this->addResource($resourceParts[0]);
                    $this->allow($role, $resourceParts[0], 'section');
                }
                if(!$this->has($resource['resource_code']))
                {
                    $this->addResource($resource['resource_code']);
                }
                $this->allow($role, $resource['resource_code'], $resource['resource_action']);
            }
        }
    }

    protected function _getResourcesByRole($role)
    {
        return $this->_getDbAdapter()
            ->select(array())
            ->from(array('g' => 'acl_groups'), null)
            ->where('group_code = ?', $role)
            ->joinInner(
                array('gp' => 'acl_group_permissions'),
                'g.group_id = gp.group_id',
                array()
            )
            ->joinInner(
                array('apr' => 'acl_permission_resources'),
                'gp.permission_id = apr.permission_id',
                array()
            )
            ->joinInner(
                array('ar' => 'acl_resources'),
                'apr.resource_id = ar.resource_id',
                array('resource_code', 'resource_action')
            )
            ->query()
            ->fetchAll(Zend_Db::FETCH_ASSOC);
    }

    protected $_db = null;
    /**
     * Get database adapter.
     *
     * @return Zend_Db_Adapter_Pdo_Mysql
     */
    protected function _getDbAdapter()
    {
        if(null === $this->_db)
        {
            $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        }
        return $this->_db;
    }

    protected static $_instance = null;
    /**
     * Get Core_Acl instance.
     *
     * @param integer $userId            UserId for which ACL will be generated.
     *
     * @static
     * @return Core_Acl
     */
    static public function getInstance($userId = null)
    {

        if(self::$_instance == null || $userId != null)
        {
            $sess = new Zend_Session_Namespace('Core_Acl');
            $aclUid = 'acl'.$userId;
            if($sess->$aclUid == null)
            {
                $sess->$aclUid = serialize(new Core_Acl($userId));
            }
            self::$_instance = unserialize($sess->$aclUid);//new Core_Acl($userId);
        }
        return self::$_instance;
    }
}