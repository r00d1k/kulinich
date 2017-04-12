<?php
class Core_View_Helper_Navigation extends Zend_View_Helper_Navigation
{
    const CORE_NS = 'Core_View_Helper_Navigation';

    /**
     * Adding Core Helper Path. and calling parent's findHelper.
     *
     * @param string  $proxy  Proxy.
     * @param boolean $strict Strict.
     *
     * @return Zend_View_Helper_Navigation_Helper
     */
    public function findHelper($proxy, $strict = true)
    {
        if(!$this->view->getPluginLoader('helper')->getPaths(self::NS))
        {
            $this->view->addHelperPath(
                str_replace('_', '/', self::NS),
                self::NS
            );
        }
        if(!$this->view->getPluginLoader('helper')->getPaths(self::CORE_NS))
        {
            $this->view->addHelperPath(
                'Core/View/Helpers/Navigation',
                self::CORE_NS
            );
        }
        return parent::findHelper($proxy, $strict = true);
    }
}