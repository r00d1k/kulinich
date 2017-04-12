<?php
/**
 * @category   Core
 * @package    Core_Translate
 */

/**
 * @category   Core
 * @package    Core_Translate
 */
class Core_Translate_Adapter_Database extends Zend_Translate_Adapter
{
    private $_data = array();

    protected $_currentModule = null;

    protected $_splitter = ':';
    /**
     * Load translation data.
     *
     * @param string|array $data    Data.
     * @param string       $locale  Locale/Language to add data for, identical with locale identifier,
     *                              see Zend_Locale for more information.
     * @param array        $options OPTIONAL Options to use.
     *
     * @return array
     */
    protected function _loadTranslationData($data, $locale, array $options = array())
    {
        $select = Zend_Db_Table_Abstract::getDefaultAdapter()->select();
        $translations = $select->from('translations', array('key' => new Zend_Db_Expr('CONCAT(`translation_module`, \'' . $this->_splitter . '\', translation_key)'), 'translation_value'))
                               ->where('translation_locale = ?', $locale)
                               ->query()
                               ->fetchAll();
        $data = array();
        $maxI = count($translations);

        for($i = 0; $i < $maxI; $i++)
        {
            $data[$translations[$i]['key']] = $translations[$i]['translation_value'];
        }
        if(!isset($this->_data[$locale]))
        {
            $this->_data[$locale] = array();
        }

        $this->_data[$locale] = $data + $this->_data[$locale];
        return $this->_data;
    }

    /**
     * Translates the given string returns the translation.
     *
     * @param string|array       $messageId Translation string, or Array for plural translations.
     * @param string|Zend_Locale $locale    Locale/Language to use, identical with
     *                                       locale identifier, @see Zend_Locale for more information.
     *
     * @return string
     */
    public function translate($messageId, $locale = null)
    {
        if($this->_currentModule == null)
        {
            $this->_currentModule = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
        }
        $prefix = '';
        if(strpos($messageId, $this->_splitter) === false)
        {
            $prefix = $this->_currentModule . $this->_splitter;
        }
        $translation = parent::translate($prefix . $messageId, $locale);

        if($translation == $prefix . $messageId)
        {
            return $messageId;
        }
        else
        {
            return $translation;
        }
    }

    /**
     * Checks if a string is translated within the source or not.
     *
     * @param string             $messageId Translation string.
     * @param boolean            $original  Allow translation only for original language
     *                                       when true, a translation for 'en_US' would give false when it can
     *                                       be translated with 'en' only.
     * @param string|Zend_Locale $locale    Locale/Language to use, identical with locale identifier,
     *                                       see Zend_Locale for more information.
     *
     * @return boolean
     */
    public function isTranslated($messageId, $original = false, $locale = null)
    {
        if($this->_currentModule == null)
        {
            $this->_currentModule = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
        }
        $prefix = '';
        if(strpos($messageId, $this->_splitter) === false)
        {
            $prefix = $this->_currentModule . $this->_splitter;
        }
        return parent::isTranslated($prefix . $messageId, $original, $locale);
    }

    /**
     * Returns the adapters name.
     *
     * @return string
     */
    public function toString()
    {
        return "Array";
    }
}