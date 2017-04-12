<?php
class System_Form_Acl_Group extends Core_Form
{
    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $mainPart = new Core_Form_SubForm();
        $mainPart
            ->addElement(
                Core_Form_Element_Text::create('name')
                    ->setLabel('system-acl-groups:name')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Text::create('code')
                    ->setLabel('system-acl-groups:code')
                    ->setRequired(true)
            )
            ->addElement(
                Core_Form_Element_Checkbox::create('isGuest')
                    ->setLabel('system-acl-groups:is_guest')
                    ->setUncheckedValue('no')
                    ->setCheckedValue('yes')
            )
            ->addElement(
                Core_Form_Element_MultiCombo::create('permissions')
                    ->setLabel('system-acl-groups:group_permissions')
                    ->setViewPlugin(null)
                    ->addDecorator(new Core_Form_Decorator_ComboList())
                    ->setMultiOptions($this->_getPermissions())
            );
        $this->addSubForm($mainPart, self::MAIN_PART);
        $this->addElement(
            Core_Form_Element_Submit::create('submit')
                ->setLabel('system-acl-groups:action_save')
                ->setAttrib('class', 'btn')
                ->addDecorator('HtmlTag', array('tag'=>'div','class'=>'controls form-foot'))
        );
    }
    protected function _getPermissions()
    {
        $all = System_Model_Mapper_Acl_Permission::getInstance()->findAll(
            array(), 'code ASC'
        );
        $permissions = array();
        $permissionChilds = array();
        foreach($all as $permission)
        {
            if($permission->parentId == null)
            {
                $permissions[$permission->id] = $permission;
            }
            else
            {
                if(!isset($permissionChilds[$permission->parentId]))
                {
                    $permissionChilds[$permission->parentId] = array();
                }
                $permissionChilds[$permission->parentId][] = $permission;
            }
        }

        $out = array();
        foreach($permissions as $permission)
        {
            if($permission->type == 'group' && !empty($permissionChilds[$permission->id]))
            {
                foreach($permissionChilds[$permission->id] as $child)
                {
                    $out[$permission->code][$child->id] = $child->code;
                }
            }
            elseif($permission->type != 'group')
            {
                $out[$permission->id] = $permission->code;
            }
        }
        return $out;
    }
}