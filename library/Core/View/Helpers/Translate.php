<?php
class Core_View_Helper_Translate extends Zend_View_Helper_Translate
{
    /**
     * Translate a message.
     *
     * You can give multiple params or an array of params.
     * If you want to output another locale just set it as last single parameter.
     * Example 1: translate('%1\$s + %2\$s', $value1, $value2, $locale).
     * Example 2: translate('%1\$s + %2\$s', array($value1, $value2), $locale).
     *
     * @param string $messageId Id of the message to be translated.
     *
     * @return string|Zend_View_Helper_Translate Translated message
     */
    public function translate($messageId = null)
    {
        if($messageId === null)
        {
            return $this;
        }

        $translate = $this->getTranslator();
        $options   = func_get_args();

        array_shift($options);
        $count  = count($options);
        $locale = null;
        if($count > 0)
        {
            if(Zend_Locale::isLocale($options[($count - 1)], null, false) !== false)
            {
                $locale = array_pop($options);
            }
        }

        if($translate->isTranslated($messageId, false, $locale))
        {
            return parent::translate($messageId, $options, $locale);
        }
        else
        {
            return parent::translate($messageId, $options, DEFAULT_LANGUAGE);
        }
    }
}