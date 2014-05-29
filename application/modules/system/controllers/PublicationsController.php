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
        $this->view->useAddDialog = false;
        parent::indexAction();
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
}