<?php
/**
 * Helper for making easy links and getting urls that depend on the routes and router.
 *
 * @package Core_View
 * @subpackage Helper
 */
include_once 'Core/View/Helpers/Url.php';

class Core_View_Helper_ActionHref extends Core_View_Helper_Url
{
    protected $_label = null;
    protected $_htmlAttribs = array();
    protected $_addReturnTo = true;
    protected $_url = array();
    protected $_route = 'default';
    protected $_resetRoute = false;
    protected $_hideIfDisallowed = true;
    protected $_prependhtml = '';
    protected $_appendhtml = '';

    /**
     *  Getting helper class.
     *
     * @param array $options Options.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function actionHref(array $options = array())
    {
        //$instance = get_class($this);
        $instance = clone $this;//new $instance();
        foreach($options as $option => $value)
        {
            $setterName = 'set'.ucfirst($option);
            if(method_exists($this, $setterName))
            {
                $instance->$setterName($value);
            }
        }
        return $instance;
    }

    /**
     *  Renderer.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     *  Renderer.
     *
     * @return string
     */
    public function render()
    {
        if(!$this->isAllowed())
        {
            if($this->_hideIfDisallowed)
            {
                return '';
            }
            else
            {
                if(empty($this->_htmlAttribs['class']))
                {
                    $this->_htmlAttribs['class'] = '';
                }
                $this->_htmlAttribs['class'] .= ' action-disabled';
            }
        }
        else
        {
            $this->_htmlAttribs['href'] = $this->getUrl();
        }
        $attribs = array();
        foreach($this->_htmlAttribs as $attrib => $value)
        {
            $value = trim($value);
            if($attrib != 'onclick')
            {
                $value = addslashes($value);
            }
            if(!empty($value))
            {
                $attribs[] = ' ' . $attrib . '="' . $value.'"';
            }
        }

        return '<a' . implode($attribs).'>' . $this->_prependhtml . $this->_label . $this->_appendhtml . '</a>';
    }

    /**
     *  Creating url for action.
     *
     * @return string
     */
    public function getUrl()
    {
        if(empty($this->_url['module']))
        {
            $this->setUrl(array());
        }
        if($this->_addReturnTo && empty($this->_url['return-to']))
        {
            $this->_url['return-to'] = base64_encode(preg_replace('%(/return-to/[^/]+/?|/return-to/?)%', '/', $this->view->serverUrl(true)));
        }
        return $this->url($this->_url, $this->_route, $this->_resetRoute);
    }

    /**
     *  Checking is action allowed by ACL.
     *
     * @return boolean
     */
    public function isAllowed()
    {
        if(empty($this->_url['module']))
        {
            $this->setUrl(array());
        }
        return Core_Util_User::hasAccess($this->_url['module'] . '::' . $this->_url['controller'], $this->_url['action']);
    }

    /**
     *  Setter.
     *
     * @param string $value Value.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function setLabel($value)
    {
        if(!empty($value))
        {
            $value = $this->view->translate($value);
        }
        $this->_label = $value;
        return $this;
    }

    /**
     *  Setter.
     *
     * @param string $value Value.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function setTitle($value)
    {
        if(!empty($value))
        {
            $value = $this->view->translate($value);
        }
        $this->_htmlAttribs['title'] = $value;
        return $this;
    }

    /**
     *  Setter.
     *
     * @param string $value Value.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function setConfirm($value)
    {
        if(trim($value) != '')
        {
            if(!empty($value))
            {
                $value = $this->view->translate($value);
            }
            $this->_htmlAttribs['confirm'] = $value;
            $this->_htmlAttribs['onclick'] = "return confirm('" . $value . "');";
        }
        return $this;
    }

    /**
     *  Setter.
     *
     * @param string $value Value.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function setClass($value)
    {
        $this->_htmlAttribs['class'] = $value;
        return $this;
    }

    /**
     *  Setter.
     *
     * @param boolean $value Value.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function setAddReturnTo($value)
    {
        $this->_addReturnTo = (bool)$value;
        return $this;
    }

    /**
     *  Setter.
     *
     * @param array $url Value.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function setUrl(array $url)
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        if(empty($url['module']))
        {
            $url['module'] = $request->getModuleName();
        }
        if(empty($url['controller']))
        {
            $url['controller'] = $request->getControllerName();
        }
        if(empty($url['action']))
        {
            $url['action'] = $request->getActionName();
        }
        $this->_url = $url;
        return $this;
    }

    /**
     *  Setter.
     *
     * @param string $value Value.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function setRoute($value)
    {
        $this->_route = $value;
        return $this;
    }

    /**
     *  Setter.
     *
     * @param boolean $value Value.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function setRouteReset($value)
    {
        $this->_resetRoute = (bool)$value;
        return $this;
    }

    /**
     *  Setter.
     *
     * @param boolean $value Value.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function setHideIfDisallowed($value)
    {
        $this->_hideIfDisallowed = (bool)$value;
        return $this;
    }

    /**
     * Prepends Html.
     *
     * @param string $html Html to prepend.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function prependHtml($html)
    {
        $this->_prependhtml = $html;
        return $this;
    }

    /**
     * Appends Html.
     *
     * @param string $html Html to append.
     *
     * @return Core_View_Helper_ActionHref
     */
    public function appendHtml($html)
    {
        $this->_appendhtml = $html;
        return $this;
    }
}