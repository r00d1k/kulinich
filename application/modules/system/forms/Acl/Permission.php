<?php

class System_Form_Acl_Permission extends Core_Form
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
        $mainPart->addElement(
            Core_Form_Element_Text::create('code')
                ->setLabel('system-acl-permissions:code')
                ->setRequired(true)
        );
        if($this->_entity == null || !$this->_entity->isLoaded())
        {
            $availableGroups = System_Model_Mapper_Acl_Permission::getInstance()->findAll(
                array(
                    'type' => 'group'
                )
            );

            $mainPart
                ->addElement(
                    Core_Form_Element_RadioButtonSet::create('type')
                    ->setLabel('system-acl-permissions:type')
                    ->setRequired(true)
                    ->addMultiOptions(
                        array(
                            'group'      => 'Group',
                            'permission' => 'Permission'
                        )
                    )
                )
                ->addElement(
                    Core_Form_Element_Combo::create('parentId')
                    ->setLabel('system-acl-permissions:group')
                    ->setRequired(false)
                    ->setMultiOptions(
                        array(null => 'no-parent') +
                        Core_Util_Array::convertForSelect(
                            $availableGroups, 'id', 'code'
                        )
                    )
                )
                ->addElement(
                    Core_Form_Element_MultiCombo::create('resources')
                    ->setLabel('system-acl-permissions:permission_resources')
                    ->addDecorator(new Core_Form_Decorator_ComboList())
                    ->setViewPlugin(null)
                    ->setAttrib('style', 'min-height:250px;')
                    ->setMultiOptions($this->_getAclResourcesForSelect())
            );
        }
        else
        {
            $mainPart->addElement(Core_Form_Element_Hidden::create('type'));
            if($this->_entity->type != 'group')
            {
                $availableGroups = System_Model_Mapper_Acl_Permission::getInstance()->findAll(
                    array(
                        'type' => 'group'
                    )
                );
                $mainPart
                    ->addElement(
                        Core_Form_Element_Combo::create('parentId')
                        ->setLabel('system-acl-permissions:group')
                        ->setRequired(false)
                        ->setMultiOptions(
                            array(null => 'no-parent') +
                            Core_Util_Array::convertForSelect(
                                $availableGroups, 'id', 'code'
                            )
                        )
                    )
                    ->addElement(
                        Core_Form_Element_MultiCombo::create('resources')
                        ->setLabel('system-acl-permissions:permission_resources')
                        ->addDecorator(new Core_Form_Decorator_ComboList())
                        ->setViewPlugin(null)
                        ->setAttrib('style', 'min-height:250px;')
                        ->setMultiOptions($this->_getAclResourcesForSelect()
                        )
                );
            }
        }

        $this->addSubForm($mainPart, self::MAIN_PART);
        $this->addElement(
            Core_Form_Element_Submit::create('submit')
                ->setLabel('system-acl-permissions:action_save')
                ->setAttrib('class', 'btn')
                ->addDecorator('HtmlTag',
                    array(
                    'tag'   => 'div',
                    'class' => 'controls form-foot'))
        );
        $this->getMainForm()->getElement('type')->setValue('group');
    }

    public function render(Zend_View_Interface $view = null)
    {
        $GUIAddon = '';
        if($this->_entity == null || !$this->_entity->isLoaded())
        {
            $GUIAddon = '
                <script type="text/javascript">

                    $("#main_part-type-buttons")
                        .find("input:radio")
                        .change(function(){
                            var self = $(this);
                            if(this.checked)
                            {
                                if(window.console != undefined)
                                {
                                    console.info("Permission type is set to:", self.val());
                                }
                                if(self.val() == "group")
                                {
                                    $("#main_part-resources").parents(".control-group").first().hide();
                                    $("#main_part-parentId").parents(".control-group").first().hide();
                                }
                                else
                                {
                                    $("#main_part-resources").parents(".control-group").first().show();
                                }
                            }
                        })
                        .each(function(){
                            if($(this).attr("checked"))
                            {
                                $(this).trigger("change");
                            }
                        });
                </script>
            ';
        }
        return parent::render($view) . $GUIAddon;
    }

    protected function _getAclResourcesForSelect()
    {
        $out = array(
        );
        foreach(System_Model_Mapper_Acl_Resource::getInstance()->findAll(array(
            ), array(
            'code ASC',
            'type ASC')) as $resource)
        {
            $out['(' . $resource->type . ') ' . $resource->code][$resource->id] = $resource->action;
        }
        return $out;
    }

}

