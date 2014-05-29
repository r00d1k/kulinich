<?php
class Core_Form_Element_RichEditor_LiveEditor extends Core_Form_Element_RichEditor_Abstract
{
    public function init()
    {
        parent::init();
        $this->getView()->headScript()
            ->appendFile($this->getView()->baseUrl().'/res/innova/scripts/innovaeditor.js');
    }
    public function render(Zend_View $view = null)
    {
        $content = parent::render();
        $editorName = str_replace('-', '', $this->getId());
        $script = 'var ' . $editorName . ' = new InnovaEditor("' . $editorName . '");'."\r\n";
        $script .= $editorName . '.width = $("#' . $this->getId() . '").width();'."\r\n";

        if(!empty($this->_pipeFields))
        {
            if(!empty($this->_editorOptions['groups']))
            {
                $this->_editorOptions['groups'][] = array(
                    'grpPipe',
                    '',
                    array(
                        'CustomTag'
                    )
                );
            }
            $this->_editorOptions['arrCustomTag'] = array();
            foreach($this->_pipeFields as $field)
            {
                $this->_editorOptions['arrCustomTag'][] = array($field, $field);
            }
        }
        $this->_editorOptions['cmdAssetManager'] = 'modalDialogShow(\'/res/innova/assetmanager/assetmanager.php\',640,480)';
        if($this->_editorOptions != null)
        {
            foreach($this->_editorOptions as $option => $value)
            {
                if(is_array($value))
                {
                    $value = Zend_Json::encode($value);
                }
                elseif(!is_numeric($value))
                {
                    $value = '"' . addslashes($value) . '"';
                }
                $script .= $editorName . '.' . $option . ' = ' . $value . ';'."\r\n";
            }
        }

        //$script .= $editorName . '.arrCustomTag = [["{%first_name%}", "{%first_name%}"],["{%last_name%}", "{%last_name%}"]];'."\r\n";

        $script .= $editorName . '.REPLACE("' . $this->getId() . '");';
        $content .= '<script type="text/javascript">' .$script . '</script>';
        return $content;
    }
}
