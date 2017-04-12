<?php
/**
 * Base for Core_Image_GD, Core_Image_Imagick and future adapters.
 *
 * @abstract
 * @class      Core_Image_Adapter_Abstract
 * @implements Core_Image_Adapter_Interface
 * @author     PMatvienko
 * @version    1.0
 * @package    Core_Image
 */
abstract class Core_Image_Adapter_Abstract implements Core_Image_Adapter_Interface
{
    /**
     * @var null|resource
     */
    protected $_source = null;

    /**
     * Constructor.
     *
     * @param null|resource $source Source of image.

     */
    public function __construct($source = null)
    {
        $this->_source = $source;
    }

    /**
     * Gets an adapter with Fitted by bigger side ratio image resource.
     *
     * @param integer $width  Width for resized image.
     * @param integer $height Height for resized image.
     *
     * @return Core_Image_Adapter_Gd
     */
    public function resizeFitBigger($width, $height)
    {
        $originalSize = $this->getSize();
        $ratios = $this->_getResizeRatios($originalSize->width, $width, $originalSize->height, $height);

        $resizeRatio = $ratios->{$ratios->bigger};
        $newWidth  = intval($resizeRatio * $originalSize->width);
        $newHeight = intval($resizeRatio * $originalSize->height);
        return $this->resize($newWidth, $newHeight);
    }


    /**
     * Gets an adapter with Fitted by smaller side ratio image resource.
     *
     * @param integer $width  Width for resized image.
     * @param integer $height Height for resized image.
     *
     * @return Core_Image_Adapter_Gd
     */
    public function resizeFitSmaller($width, $height)
    {
        $originalSize = $this->getSize();
        $ratios = $this->_getResizeRatios($originalSize->width, $width, $originalSize->height, $height);

        $resizeRatio = $ratios->{$ratios->smaller};
        $newWidth  = intval($resizeRatio * $originalSize->width);
        $newHeight = intval($resizeRatio * $originalSize->height);
        return $this->resize($newWidth, $newHeight);
    }

    /**
     * Gets an adapter with image resource that was resized by bigger ratio and cropped to given size.
     *
     * @param integer $width  Width for resized image.
     * @param integer $height Height for resized image.
     *
     * @abstract
     * @return Core_Image_Adapter_Interface
     */
    public function resizeWithCrop($width, $height)
    {
        $resized = $this->resizeFitBigger($width, $height);
        $resizedRalSize = $resized->getSize();

        $cropTLX = 0;
        $cropTLY = 0;
        $cropBRX = $resizedRalSize->width;
        $cropBRY = $resizedRalSize->height;

        if($resizedRalSize->width > $width)
        {
            $cropTLX = intval(($resizedRalSize->width - $width)/2);
            $cropBRX = $cropTLX + $width;
        }
        if($resizedRalSize->height > $height)
        {
            $cropTLY = intval(($resizedRalSize->height - $height)/2);
            $cropBRY = $cropTLY + $height;
        }
        $resized = $resized->crop($cropTLX, $cropTLY, $cropBRX, $cropBRY);
        return $resized;
    }

    /**
     * Gets resize rations for FIT_SMALLER and FIT_BIGGER resize types.
     *
     * @param integer $oldWidth  Original image width.
     * @param integer $newWidth  Resized image width.
     * @param integer $oldHeight Original image height.
     * @param integer $newHeight New image Height.
     *
     * @return stdClass
     */
    protected function _getResizeRatios($oldWidth, $newWidth, $oldHeight, $newHeight)
    {
        $out = new stdClass();
        $out->width = $newWidth/$oldWidth;
        $out->height = $newHeight/$oldHeight;
        $out->smaller = (($out->width < $out->height) ? 'width' : 'height');
        $out->bigger = (($out->smaller == 'width') ? 'height' : 'width');
        return $out;
    }

    /**
     * This function detecting MIME type of File.
     *
     * @param string $path Path to a file.
     *
     * @return string
     */
    protected function _getFileMimeType($path)
    {
        $mime = finfo_file(finfo_open(FILEINFO_MIME), $path);
        $delimiterPos = strpos($mime, ';');
        if($delimiterPos !== false)
        {
            $mime = substr($mime, 0, $delimiterPos);
        }
        return $mime;
    }

    /**
     * This function returns a source adapter.
     *
     * @return boolean|resource|Imagick
     * @throws Core_Image_Exception Throws Core_Image_Exception if image not set.
     */
    protected function _getSource()
    {
        if($this->_source == null)
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Image not set.');
        }
        $source = &$this->_source;
        return $source;
    }
}