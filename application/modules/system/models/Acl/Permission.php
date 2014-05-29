<?php

class System_Model_Acl_Permission extends Core_Entity_Model_Abstract
{

    public function getForm()
    {
        $form = $this->getMapper()->getForm($this);
        $data = $this->toArray();
        foreach($this->resources as $group)
        {
            $data['resources'][] = $group->id;
        }
        $form->populate($data);
        return $form;
    }

    public function delete()
    {
        $children = $this->children;
        $children->parentId = null;
        $children->save();
        $this->_data['children'] = null;
        $this->_dataOriginal['children'] = null;
        return parent::delete();
    }

}

