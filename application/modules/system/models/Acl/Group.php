<?php
class System_Model_Acl_Group extends Core_Entity_Model_Abstract
{
    public function getForm()
    {
        $form = $this->getMapper()->getForm($this);
        $data = $this->toArray();
        foreach($this->permissions as $group)
        {
            $data['permissions'][] = $group->id;
        }
        $form->populate($data);
        return $form;
    }
}