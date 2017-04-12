<?php
class System_PublicationsController extends Core_Controller_Action_Scaffold
{
    protected $_mapper = 'System_Model_Mapper_Publication';
    protected $_captions = array(
        'index' => 'navigation-contents:publications',
        'create' => 'navigation:publications-create',
        'edit'   => 'navigation:publications-edit'
    );

    public function indexAction()
    {
        $this->view->grid = $this->_mapper->getGrid();
        $this->view->caption = !empty($this->_captions['index']) ? $this->_captions['index'] : 'list';
        $this->view->addAction = !empty($this->_captions['create']) ? $this->_captions['create'] : null;
        $this->view->useAddDialog = false;
    }

    public function handleUploadAction()
    {
        $uploader = new Core_FileUpload_FineUploader();
        $resultFileName = uniqid('publication_');
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