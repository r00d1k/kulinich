<?php
class System_StaticPagesController extends Core_Controller_Action_Scaffold
{
    protected $_mapper = 'System_Model_Mapper_Static_Page';
    protected $_captions = array(
        'index' => 'navigation:static-pages',
        'create' => 'navigation:static-pages-create',
        'edit'   => 'navigation:static-pages-edit'
    );
    public function indexAction()
    {
        $this->view->useAddDialog = false;
        parent::indexAction();
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
}