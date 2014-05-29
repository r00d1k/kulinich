<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Controller_Action_Scaffold
 * @subpackage Controller
 * @use Core_Controller_Action_Abstract
 *
 * This Controller contains base actions to work with entities.
 * Added to simplify building of admin side.
 */
class Core_Controller_Action_Scaffold extends Core_Controller_Action_Abstract
{
    /**
     * @var Core_Entity_Mapper_Abstract
     */
    protected $_mapper = null;
    /**
     * @var array
     */
    protected $_captions = array(
        'index' => 'list',
        'create' => 'create',
        'edit'   => 'edit'
    );

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
        if($this->_mapper != null && is_string($this->_mapper))
        {
            $mapper = $this->_mapper;
            $this->_mapper = $mapper::getInstance();
        }
        $this->view->addScriptPath(dirname(__FILE__) . '/Scaffold/');
        $this->view->useAddDialog = true;
    }

    /**
     * Index Action.
     *
     * Building grid by mapper.
     */
    public function indexAction()
    {
        $this->view->grid = $this->_mapper->getGrid();
        $this->view->caption = !empty($this->_captions['index']) ? $this->_captions['index'] : 'list';
        $this->view->addAction = !empty($this->_captions['create']) ? $this->_captions['create'] : null;
        echo $this->view->render('index.phtml');
        $this->_disableRender();
    }


    /**
     * Adding new entity
     */
    public function createAction()
    {
        $model = $this->_mapper->getModel();
        $form = $model->getForm();
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
        $form = $model->getForm();
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

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id',null);
        $model = $this->_mapper->find($id);
        if($model == null)
        {
            throw new Zend_Controller_Action_Exception('Page not found');
        }
        $model->delete();
        $this->redirect($this->view->url(array('action' => 'index')));
        exit();
    }
}