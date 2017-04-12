<?php
class System_LanguagesController extends Core_Controller_Action_Scaffold
{
    /** @var System_Model_Mapper_Language string */
    protected $_mapper = 'System_Model_Mapper_Language';
    protected $_captions = array(
        'index' => 'navigation:system-languages',
        'create' => 'navigation:system-languages-create',
        'edit'   => 'navigation:system-languages-edit'
    );

    public function createAction()
    {
        $model = $this->_mapper->getModel();
        /** @var Core_Form $form  */
        $form = $model->getForm();
        $form->getSubForm(Core_Form::MAIN_PART)->getElement('id')
            ->addValidator(
                new Core_Validate_Unique(
                    array(
                        'type' => 'mysql',
                        'field' => $this->_mapper->map('id'),
                        'table' =>$this->_mapper->getStorage()->info(Core_Entity_Storage_DbSelect::NAME)
                    )
                )
            );

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
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id',null);
        $model = $this->_mapper->find($id);
        if($model == null)
        {
            throw new Zend_Controller_Action_Exception('Page not found');
        }
        /** @var Core_Form $form  */
        $form = $model->getForm();
        $form->getMainForm()->removeElement('id');
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
        $this->view->caption = !empty($this->_captions['edit']) ? $this->_captions['edit'] : 'edit';
        $this->view->form = $form;
        $this->view->model = $model;
        echo $this->view->render('edit.phtml');
        $this->_disableRender();
    }
}