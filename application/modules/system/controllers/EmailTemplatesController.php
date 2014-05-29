<?php
/**
 * Controller to manage System Emails.
 *
 * @author P.Matvinko
 */
class System_EmailTemplatesController extends Core_Controller_Action_Scaffold
{
    /** @var System_Model_Mapper_Email_Template */
    protected $_mapper = 'System_Model_Mapper_Email_Template';
    protected $_captions = array(
        'index' => 'navigation:system-emails',
        'create' => 'navigation:system-emails-create',
        'edit'   => 'navigation:system-emails-edit'
    );
    public function indexAction()
    {
        $this->view->useAddDialog = false;
        parent::indexAction();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id',null);
        $model = $this->_mapper->find($id);
        if($model == null)
        {
            throw new Zend_Controller_Action_Exception('Page not found');
        }
        $form = $model->getForm();
        $vars = $model->variables;
        if(!empty($vars))
        {
            $vars = explode(',', $vars);
            foreach($form->getMainForm()->getSubForm('locales')->getSubForms() as $locale)
            {
                $locale->getElement('subject')->setPipeFields($vars);
                $locale->getElement('htmlBody')->setPipeFields($vars);
                $locale->getElement('textBody')->setPipeFields($vars);
            }
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
            return true;
        }
        $this->view->caption = !empty($this->_captions['edit']) ? $this->_captions['edit'] : 'edit';
        $this->view->form = $form;
        $this->view->model = $model;
        echo $this->view->render('edit.phtml');
        $this->_disableRender();
    }
}