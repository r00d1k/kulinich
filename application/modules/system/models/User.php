<?php
class System_Model_User extends Core_Entity_Model_Abstract
{
    protected $_groups = null;
    public function getForm()
    {
        $form = $this->getMapper()->getForm($this);
        $data = $this->toArray();
        foreach($this->groups as $group)
        {
            $data['groups'][] = $group->id;
        }
        $form->populate($data);
        return $form;
    }

   /* public function setFormData(array $data)
    {
        if($this->getKey() == null)
        {
            $this->_groups = $data['groups'];
            unset($data['groups']);
        }
        return parent::setFormData($data);
    }
    public function save()
    {
        $result = parent::save();
        if($result && !empty($this->_groups))
        {
            $this->setFormData(array('groups' => $this->_groups));
            $this->_groups = null;
        }
        return $result;
    }*/
}