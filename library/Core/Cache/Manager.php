<?php
/**
 * @package Core_Cache
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Cache_Manager
 * @use Zend_Cache_Manager
 *
 * Main Bootstrap
 */
class Core_Cache_Manager extends Zend_Cache_Manager
{
    /**
     * @return array
     */
    public function getCacheTemplateNames()
    {
        return array_keys( $this->_optionTemplates );
    }
}
