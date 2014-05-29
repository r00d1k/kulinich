<?php
class System_AboutController extends Core_Controller_Action_Scaffold
{
    /** @var System_Model_Mapper_Gallery_Photo  */
    protected $_mapper = 'System_Model_Mapper_Photo';
    protected $_captions = array(
        'index' => 'navigation-contents:about',
        'create' => 'navigation:about-add-photo',
        'edit'   => 'navigation:about-edit-photo'
    );

    public function indexAction()
    {
        $req    = $this->_mapper->getDataRequest();
        $req->where('gallery', 'about');
        $config = $this->_mapper->getConfig();

        $this->view->grid = new Core_Grid_Html($req, $config['grid']);
        $this->view->caption = !empty($this->_captions['index']) ? $this->_captions['index'] : 'list';
        $this->view->addAction = !empty($this->_captions['create']) ? $this->_captions['create'] : null;
    }

    public function createAction()
    {
        $model = $this->_mapper->getModel();
        $form = $model->getForm();
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
        {
            $data = $form->getValues();
            $model->setFormData($data);
            $model->gallery = 'about';
            $model->rank = $this->_mapper->getCount(array('gallery'=> 'about'));
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
}