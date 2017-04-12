<?php
/**
 * @package Core_Application
 * @author P.Matvienko
 * @project W.A.C.
 * @class Core_Controller_Plugin_Multilanguage
 * @subpackage Controller_Plugin
 * @use Zend_Controller_Plugin_Abstract
 *
 * Multilanguage Urls
 */
class Core_Controller_Plugin_Multilanguage extends Zend_Controller_Plugin_Abstract
{
    protected $_table = 'languages';
    protected $_languagesAvailable = null;
    protected $_enaleLanguageDetection = false;
    protected $_localeField = 'language_locale';
    protected $_idField = 'language_id';
    protected $_isAvailableField = 'language_is_avaliable';

    /**
     *  On route startup.
     *
     * @param Zend_Controller_Request_Abstract $request A request instance.
     *
     * @return void
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->_loadConfig();
        $languages = $this->_getAvaliableLanguages();
        if(count($this->_getAvaliableLanguages()) > 1)
        {
            $this->_fixRequestUri($request);
            $this->_chainRoutes();
        }
        else
        {
            $language = current($languages);
            $lngCode = $language->{$this->_idField};
            $request->setParam('language', $lngCode);
            Zend_Controller_Front::getInstance()->getRequest()->setParam('language-locale', $lngCode . '_' . strtoupper($lngCode));
        }
    }

    /**
     *  Loading plugin's config.
     *
     * @return void
     */
    protected function _loadConfig()
    {
        $options = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();

        $config = array();
        if(isset($options['resources']['frontController']['pluginSettings']['multilanguage']))
        {
            $config = $options['resources']['frontController']['pluginSettings']['multilanguage'];
        }
        $this->_table = empty($config['table'])?'languages':$config['table'];
        $this->_enaleLanguageDetection = empty($config['languageDetection'])?false:true;
        $this->_localeField = empty($config['localeField'])?'language_locale':$config['localeField'];
        $this->_idField = empty($config['idField'])?'language_id':$config['idField'];
        $this->_isAvailableField = empty($config['isAvailableField'])?'language_is_avaliable':$config['isAvailableField'];
    }

    /**
     *  On shut down route.
     *
     * @param Zend_Controller_Request_Abstract $request A request instance.
     *
     * @return void
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        if(count($this->_getAvaliableLanguages()) > 1)
        {
            $language = $request->getParam('language');
            if(!empty($language) and DEFAULT_LANGUAGE != $language)
            {
                $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
                $locale = $bootstrap->getResource('locale');
                $translate = $bootstrap->getResource('translate');

                $locale->setLocale($language);
                $translate->addTranslation(array('content' => $language, 'locale' => $language));
                $translate->setLocale($locale);
                Core_Entity_Mapper_Abstract::setLocale($locale);
                Zend_Controller_Front::getInstance()
                    ->getRequest()
                    ->setParam('language-locale', $this->_languagesAvailable[$language]->{$this->_localeField})
                    ->setParam('language', $language);
            }
            elseif(!empty($language) and DEFAULT_LANGUAGE == $language)
            {
                $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
                $locale = $bootstrap->getResource('locale');
                $translate = $bootstrap->getResource('translate');

                $locale->setLocale($language);
                $translate->setLocale($locale);
                Core_Entity_Mapper_Abstract::setLocale($locale);
                Zend_Controller_Front::getInstance()
                    ->getRequest()
                    ->setParam('language-locale', $this->_languagesAvailable[$language]->{$this->_localeField})
                    ->setParam('language', $language);
            }
        }
    }

    /**
     *  Fixing Uri.
     *
     * @param Zend_Controller_Request_Abstract $request A request instance.
     *
     * @return void
     */
    protected function _fixRequestUri(Zend_Controller_Request_Abstract $request)
    {
        $requestUri = $request->getRequestUri();

        $uriWithoutBase = substr($requestUri, strlen($request->getBaseUrl()));
        $uriArray       = explode('/', trim($uriWithoutBase, '/'));

        $defaultLanguageCode = $this->_getClientDefaultLanguage()->{$this->_idField};
        if($defaultLanguageCode == null || !$this->_enaleLanguageDetection)
        {
            $defaultLanguageCode = DEFAULT_LANGUAGE;
        }
        if(strlen($uriArray[0]) != 2)
        {
            array_unshift($uriArray, $defaultLanguageCode);
            $uriWithoutBase = implode('/', $uriArray);
            if(!$this->_enaleLanguageDetection)
            {
                Zend_Controller_Front::getInstance()
                        ->getRequest()
                        ->setRequestUri($request->getBaseUrl() . '/' . $uriWithoutBase);
                Zend_Controller_Front::getInstance()
                    ->getRequest()
                    ->setParam('language-locale', $this->_languagesAvailable[$defaultLanguageCode]->{$this->_localeField});
            }
            else
            {
                Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')
                ->gotoUrl($request->getBaseUrl() . '/' . $uriWithoutBase);
            }
        }
        elseif(!$this->_isLAnguageAvaliable($uriArray[0]))
        {
            if(!$this->_enaleLanguageDetection)
            {
                unset($uriArray[0]);
                $uriWithoutBase = implode('/', $uriArray);
                Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')
                    ->gotoUrl($request->getBaseUrl() . '/' . $uriWithoutBase);
            }
            else
            {
                $uriArray[0] = $defaultLanguageCode;
                $uriWithoutBase = implode('/', $uriArray);
                Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')
                    ->gotoUrl($request->getBaseUrl() . '/' . $uriWithoutBase);
            }
        }
    }

    /**
    *   Checking is language available.
    *
    * @param string $languageId A language Id.
     *
    * @return boolean
    */
    protected function _isLAnguageAvaliable($languageId)
    {
        foreach($this->_getAvaliableLanguages() as $language)
        {
            if($language->{$this->_idField} == $languageId)
            {
                return true;
            }
        }
        return false;
    }

    /**
     *  Chainig routes.
     *
     * @return void
     */
    protected function _chainRoutes()
    {
        $languageRoute = new Zend_Controller_Router_Route(
            ':language',
            array('language' => DEFAULT_LANGUAGE),
            array('language' => '[a-z]{2}')
        );

        $router = Zend_Controller_Front::getInstance()->getRouter();
        $rotes = array();
        foreach($router->getRoutes() as $name => $route)
        {
            $rotes[$name] = $languageRoute->chain($route);
            $router->removeRoute($name);
        }
        $router->addRoutes($rotes);
    }

    /**
     * Gets an available languages list.
     *
     * @return array
     */
    protected function _getAvaliableLanguages()
    {
        if($this->_languagesAvailable === null)
        {
            $table = new Zend_Db_Table(array('name' => $this->_table));
            $data = $table->fetchAll(array($this->_isAvailableField.' = \'yes\''));
            $this->_languagesAvailable = array();
            foreach($data as $language)
            {
                $this->_languagesAvailable[$language->{$this->_idField}] = $language;
            }
        }
        return $this->_languagesAvailable;
    }

    /**
     *  Gets a language from available list that understandable for user's browser.
     *
     * @return null|Zend_Db_Table_Row
     */
    protected function _getClientDefaultLanguage()
    {
        $laguageCodes = array();
        $sysDefaultLanguage = null;
        foreach($this->_getAvaliableLanguages() as $lang)
        {
            if($lang->{$this->_idField} == DEFAULT_LANGUAGE)
            {
                $sysDefaultLanguage = $lang;
            }
            $laguageCodes[$lang->{$this->_localeField}] = $laguageCodes[$lang->{$this->_idField}] = $lang;
        }

        foreach(Zend_Locale::getBrowser() as $localeCode => $quality)
        {
            if(!empty($laguageCodes[$localeCode]))
            {
                return $laguageCodes[$localeCode];
            }
        }
        if(!empty($sysDefaultLanguage))
        {
            return $sysDefaultLanguage;
        }
        if(count($this->_languagesAvailable) > 0)
        {
            return $this->_languagesAvailable[0];
        }
        return null;
    }
}