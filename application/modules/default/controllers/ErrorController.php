<?php
class ErrorController extends Core_Controller_Action_Abstract
{
    /**
     * Error Action.
     *
     * @return void
     */
    public function errorAction()
    {
        // Grab the error object from the request
        $errors = $this->_getParam('error_handler');

        if(
            $errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER ||
            $errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION ||
            $errors->code = 404
        )
        {
            // 404 error -- controller or action not found
            $this->getResponse()->setHttpResponseCode(404);
            $this->view->message = 'Page not found';
        }
        else
        {
            $this->getResponse()->setHttpResponseCode(500);
            $this->view->message = 'Application error';
        }

        // pass the actual exception object to the view
        $this->view->exception = $errors->exception;

        // pass the request to the view
        $this->view->request = $errors->request;
    }
}