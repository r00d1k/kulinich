<?php
class System_ClinicCasesGalleryController extends Core_Controller_Action_Scaffold
{
    /** @var System_Model_Mapper_Clinic_Photo  */
    protected $_mapper = 'System_Model_Mapper_Clinic_Photo';
    protected $_captions = array(
        'index' => 'navigation:clinic-photos',
        'create' => 'navigation:clinic-photos-create',
        'edit'   => 'navigation:clinic-photos-edit'
    );

    public function indexAction()
    {
        $case = System_Model_Mapper_Clinic_Case::getInstance()->find($this->getRequest()->getParam('case-id', null));
        if($case == null)
        {
            throw new Zend_Controller_Action_Exception('Клинический случай не найден', 404);
        }
        $this->view->case = $case;
        $req    = $this->_mapper->getDataRequest();
        $req->where('caseId', $case->getKey());
        $config = $this->_mapper->getConfig();
        //$this->getRequest()->setParam('return-to', null);
        $this->view->grid = new Core_Grid_Html($req, $config['grid']);
        $this->view->caption = !empty($this->_captions['index']) ? $this->_captions['index'] : 'list';
        $this->view->addAction = !empty($this->_captions['create']) ? $this->_captions['create'] : null;
    }

    public function createAction()
    {
        $case = System_Model_Mapper_Clinic_Case::getInstance()->find($this->getRequest()->getParam('case-id', null));
        if($case == null)
        {
            throw new Zend_Controller_Action_Exception('Клинический случай не найден', 404);
        }
        $model = $this->_mapper->getModel();
        $form = $model->getForm();
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
        {
            $data = $form->getValues();
            $model->setFormData($data);
            $model->caseId = $case->getKey();
            $model->rank = $this->_mapper->getCount(array('caseId'=> $case->getKey()));
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
    }

    public function handleUploadAction()
    {
        $uploader = new Core_FileUpload_FineUploader();
        $resultFileName = uniqid('case_photo_');
        $result = $uploader->handleUpload(PUBLIC_PATH . '/assets/tmp/', $resultFileName);
        if(!empty($result['resultFile']))
        {
            $result['resultFile'] = '/assets/tmp/'.$result['resultFile'];
        }

        echo Zend_Json::encode($result);
        $this->_disableLayout()->_disableRender();
    }
    public function reorderItemsAction()
    {
        $items = $this->getRequest()->getParam('items');

        if(is_array($items))
        {
            $models = $this->_mapper->findAll(
                array(
                     'id' => array(
                         Core_Entity_Mapper_Abstract::FILTER_FIELD => 'id',
                         Core_Entity_Mapper_Abstract::FILTER_COND => 'IN',
                         Core_Entity_Mapper_Abstract::FILTER_VALUE => array_keys($items)
                     )
                )
            );

            foreach($models as $model)
            {
                if(array_key_exists($model->getKey(), $items))
                {
                    $model->rank = $items[$model->getKey()];
                    $model->save();
                }
            }
        }
        echo "success";
        $this->_disableLayout()->_disableRender();
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id',null);
        $model = $this->_mapper->find($id);
        $caseId = $model->case->getKey();
        if($model == null)
        {
            throw new Zend_Controller_Action_Exception('Page not found');
        }
        $model->delete();
        $this->redirect($this->view->url(array('module' => 'system', 'controller' => 'clinic-cases-gallery','action' => 'index', 'case-id' =>$caseId), 'default', true));
        exit();
    }
}