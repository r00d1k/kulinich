<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Application_Resource_Translate
 * @subpackage Resource
 * @uses Zend_Application_Resource_Translate
 *
 * Translate
 */
class Core_Application_Resource_Translate extends Zend_Application_Resource_Translate
{
    /**
     * Defined by Zend_Application_Resource_Resource.
     *
     * @return Zend_Translate
     */
    public function init()
    {
        $options = $this->getOptions();
        if(!empty($options['log']))
        {
            $logManager = $this->getBootstrap()->getResource('logManager');
            if(!empty($logManager))
            {
                $options['log'] = $logManager->getLog($options['log']);
            }
            else
            {
                unset($options['log']);
            }

            $this->setOptions($options);
        }
        else
        {
            unset( $this->_options['log'] );
        }
        return $this->getTranslate();
    }
}