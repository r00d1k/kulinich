<?php
class Core_Grid_Html_CellsSet_Actions  extends Core_Grid_Html_CellsSet_Abstract
{
    protected $_splitter = '';

    public function setSplitter($splitter)
    {
        $this->_splitter = $splitter;
        return $this;
    }

    protected $_isHidden = null;
    public function isHidden()
    {
        if($this->_isHidden === null)
        {
            $this->_isHidden = true;
            foreach($this->_content as $actionButton)
            {
                if($this->_isAllowed($actionButton))
                {
                    $this->_isHidden = false;
                    break;
                }
            }
        }
        return $this->_isHidden;
    }

    public function renderCellContent($values)
    {

        $actions = array();
        foreach($this->_content as $actionButton)
        {
            if($this->_isAllowed($actionButton))
            {
                $actions[] = $this->_htmlifyActionItem($actionButton, $values);
            }
        }
        return implode($this->_splitter, $actions);
    }

    protected function _isAllowed($settings)
    {
        $req = Zend_Controller_Front::getInstance()->getRequest();
        if(empty($settings['url']['module']))
        {
            $settings['url']['module'] = $req->getModuleName();
        }
        if(empty($settings['url']['controller']))
        {
            $settings['url']['controller'] = $req->getControllerName();
        }
        if(empty($settings['url']['action']))
        {
            $settings['url']['action'] = $req->getActionName();
        }
        return Core_Util_User::hasAccess($settings['url']['module'].'::'.$settings['url']['controller'], $settings['url']['action']);
    }

    protected function _htmlifyActionItem($settings, $values)
    {

        $req = Zend_Controller_Front::getInstance()->getRequest();
        if(is_array($settings['url']))
        {
            foreach($settings['url'] as $key=>$val)
            {
                $settings['url'][$key] = $this->_compileContent($values, $val);
            }
            if(empty($settings['url']['module']))
            {
                $settings['url']['module'] = $req->getModuleName();
            }
            if(empty($settings['url']['controller']))
            {
                $settings['url']['controller'] = $req->getControllerName();
            }
            if(empty($settings['url']['action']))
            {
                $settings['url']['action'] = $req->getActionName();
            }

            if(empty($settings['url']['return-to']))
            {
                $settings['url']['return-to'] = base64_encode(preg_replace('%(/return-to/[^/]+/?|/return-to/?)%', '/', Core_Grid_Html_Abstract::getView()->serverUrl(true)));
            }
        }

        if(empty($settings['route']))
        {
            $settings['route'] = 'default';
        }
        if(empty($settings['resetUrl']))
        {
            $settings['resetUrl'] = true;
        }
        if(is_array($settings['url']))
        {
            $url = Core_Grid_Html_Abstract::getView()->url($settings['url'], $settings['route'], $settings['resetUrl']);
        }

        /** @var Zend_Translate $translate  */
        $translate = Core_Grid_Html_Abstract::getTranslate();

        if(!empty($settings['label']))
        {
            if($translate != null)
            {
                $settings['label'] = $translate->_(
                    ($this->_translateSection != null) ?
                        $this->_translateSection . ':' . $settings['label'] :
                        $settings['label']
                );
            }
        }
        else
        {
            $settings['label'] = '';
        }
        if(!empty($settings['title']))
        {
            if($translate != null)
            {
                $settings['title'] = $translate->_(
                    ($this->_translateSection != null) ?
                        $this->_translateSection . ':' . $settings['title'] :
                        $settings['title']
                );
            }
            $settings['title'] = ' title="'.addslashes($settings['title']).'"';
        }
        else
        {
            $settings['title'] = '';
        }
        if(!empty($settings['confirm']))
        {
            if($translate != null)
            {
                $settings['confirm'] = $translate->_(
                    ($this->_translateSection != null) ?
                        $this->_translateSection . ':' . $settings['confirm'] :
                        $settings['confirm']
                );
            }
            $settings['confirm'] = ' onclick="return confirm(\''.addslashes($settings['confirm']).'\')"';
        }
        else
        {
            $settings['confirm'] = '';
        }
        $htmlAttributes = '';
        if(!empty($settings['htmlAttributes']))
        {
            $htmlAttributes = $settings['htmlAttributes'];
            if(is_array($htmlAttributes))
            {
                foreach($htmlAttributes as $k=>$v)
                {
                    $htmlAttributes[$k] = $k . '="' . addslashes($v) . '"';
                }
            }
            $htmlAttributes = ' ' . implode(' ', $htmlAttributes);
        }


        return '<a href="'.$url.'"'.$settings['title'] . $settings['confirm'] . $htmlAttributes . '>'.$settings['label'].'</a>';
    }

    public function renderCell($row)
    {
        return '<td class="actions-cell">' . $this->renderCellContent($row) . '</td>';
    }
}









