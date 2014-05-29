<?php
/**
 * Core component to work with images.
 *
 * @class   Core_Image
 * @author  PMatvienko
 * @version 1.0
 * @package Core_Image
 * @todo    Extend functionality
 */
final class Core_Image extends Core_Image_Abstract
{
    /**
     * Factory method to create Core_Image instances faster.
     *
     * @param null|string                             $imageSource An imageSource can be path to a file or serialized resource or null.
     * @param null|string|Core_Image_Adapter_Abstract $adapter     Graphics Adapter class name Or an Adapter class instance or null to use autodetected adapter.
     *
     * @throws Zend_Exception On error.
     * @return Core_Image
     */
    public static function factory($imageSource = null, $adapter = null)
    {
        return new self($imageSource, $adapter);
    }
}
