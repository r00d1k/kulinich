<?php

class System_AclPermissionsController extends Core_Controller_Action_Scaffold
{
    /** @var System_Model_Mapper_Acl_Permission  */
    protected $_mapper = 'System_Model_Mapper_Acl_Permission';
    protected $_captions = array(
        'index'  => 'navigation:system-acl-permissions',
        'create' => 'navigation:system-acl-permissions-create',
        'edit'   => 'navigation:system-acl-permissions-edit'
    );

    public function createAction()
    {
        $parentId = $this->getRequest()->getParam('parentId', null);
        $model = $this->_mapper->getModel();
        $form = $model->getForm();
        if($parentId != null)
        {
            $form->getMainForm()->getElement('type')->setMultiOptions(
                array(
                     'permission' => 'Permission'
                )
            )->setValue('permission');
            $form->getMainForm()->getElement('parentId')->setValue($parentId);
        }
        else
        {
            $form->getMainForm()->getElement('type')->setValue('group');
        }
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
        {
            $data = $form->getValues();
            $model->setFormData($data);
            $model->save();
            $this->_actionSuccess(
                $this->view->url(array('action' => 'index')),
                array(
                     'success' => true,
                     'reload'  => true,
                )
            );
        }

        $this->view->caption = !empty($this->_captions['create']) ? $this->_captions['create'] : 'create';
        $this->view->form = $form;
        $this->view->model = $model;
        echo $this->view->render('edit.phtml');
        $this->_disableRender();
    }

    public function indexAction()
    {
        $all = System_Model_Mapper_Acl_Permission::getInstance()->findAll(
            array(), 'code ASC'
        );
        $this->view->caption = !empty($this->_captions['index']) ? $this->_captions['index'] : 'list';
        $this->view->addAction = !empty($this->_captions['create']) ? $this->_captions['create'] : null;

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
        foreach($permissionChilds as $parentId => $children)
        {
            $permissions[$parentId]->children = $children;
        }
        $this->view->permissions = $permissions;
    }

    public function setParentAction()
    {
        $permission = System_Model_Mapper_Acl_Permission::getInstance()->find(
            $this->getRequest()->getParam('id', null)
        );
        $parent = System_Model_Mapper_Acl_Permission::getInstance()->find(
            $this->getRequest()->getParam('parent', null)
        );
        if($permission == null)
        {
            throw new Zend_Controller_Action_Exception('Permission not found', 404);
        }
        $permission->parentId = ($parent == null) ? null : $parent->getKey();
        $permission->save();
        $this->_disableLayout()->_disableRender();
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id',null);
        $model = $this->_mapper->find($id);
        if($model == null)
        {
            throw new Zend_Controller_Action_Exception('Page not found');
        }
        $children = $this->_mapper->findAll(array('parentId' => $model->getKey()))->delete();
        $model->delete();
        $this->redirect($this->view->url(array('action' => 'index')));
        exit();
    }
}

