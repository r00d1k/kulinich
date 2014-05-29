<?php
/**
 * Helper for making easy links and getting urls that depend on the routes and router.
 *
 * @package Core_Controller_Action
 * @subpackage Helper
 */
class Core_Controller_Action_Helper_Url extends Zend_Controller_Action_Helper_Url
{
    /**
     * Assembles a URL based on a given route.
     *
     * This method will typically be used for more complex operations, as it
     * ties into the route objects registered with the router.
     *
     * @param null|array $urlOptions Options passed to the assemble method of the Route object.
     * @param mixed      $name       The name of a Route to use. If null it will use the current Route.
     * @param boolean    $reset      Whether to reset to the route defaults ignoring URL params.
     * @param boolean    $encode     Tells to encode URL parts on output.
     *
     * @return string Url for the link href attribute.
     */
    public function url($urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        if($request->getParam('multilanguage-disabled', false) != true)
        {
            if($request->getParam('language-detection-enabled', false) == false)
            {
                if(
                   (empty($urlOptions['language']) && DEFAULT_LANGUAGE == $request->getParam('language'))
                   || (isset($urlOptions['language']) && DEFAULT_LANGUAGE == $urlOptions['language'])
                   || (isset($urlOptions['language']) && strlen($urlOptions['language']) > 2)
                )
                {
                    $urlOptions['language'] = '';
                }
                else if(empty($urlOptions['language']) && DEFAULT_LANGUAGE != $request->getParam('language'))
                {
                    $urlOptions['language'] = $request->getParam('language');
                }
            }
            else
            {
                $urlOptions['language'] = $request->getParam('language');
            }
        }
        return preg_replace('/[\/]{2,}/', '/', parent::url($urlOptions, $name, $reset, $encode));
    }
}
