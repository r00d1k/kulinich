<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Controller_Plugin_HtmlCompressor
 * @subpackage Controller_Plugin
 * @use Zend_Controller_Plugin_Abstract
 *
 * HTML Compressor
 */
class Core_Controller_Plugin_HtmlCompressor extends Zend_Controller_Plugin_Abstract
{
    /**
     * Dispatch loop shutdown.
     *
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        $response = $this->getResponse();
        $body     = $response->getBody();
        $body     = Core_Util_HtmlCompressor::compress($body);
        $response->setBody($body);
    }
}