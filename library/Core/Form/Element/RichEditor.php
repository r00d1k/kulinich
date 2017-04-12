<?php
class Core_Form_Element_RichEditor
{
    CONST EDITOR_NS = 'Core_Form_Element_RichEditor_';
    protected function __construct()
    {

    }
    public static function create($name, $preset)
    {
        $config = new Zend_Config_Yaml(CONFIG_PATH.'/form.yml', APPLICATION_ENV);
        if($config->richEditor == null || $config->richEditor->$preset == null)
        {
            throw new Zend_Form_Exception('Config section noy found for RichEditor preset "'.$preset.'"');
        }
        $config = $config->richEditor->$preset;
        /** @var Core_Form_Element_RichEditor_Abstract $editor  */
        $editor = self::EDITOR_NS . $config->editor;
        $editor = new $editor($name);
        $editor->setEditorOptions($config->options->toArray());
        return $editor;
    }
}
