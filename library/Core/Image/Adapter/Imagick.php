<?php
/**
 * Adapter for php_Imagick graphics library for Core_Image.
 *
 * @class      Core_Image_Adapter_Imagick
 * @implements Core_Image_Adapter_Interface
 * @extends    Core_Image_Adapter_Abstract
 * @author     PMatvienko
 * @version    1.0
 * @package    Core_Image
 */
class Core_Image_Adapter_Imagick extends Core_Image_Adapter_Abstract
{
    /**
     * Creates a new image resource for self.
     *
     * @param integer $width           New image width.
     * @param integer $height          New image height.
     * @param string  $backgroundColor Background color for text.
     * @param boolean $returnSource    Just create and return source.
     *
     * @return Core_Image_Adapter_Imagic
     */
    public function createNewResource($width, $height, $backgroundColor, $returnSource = false)
    {
        $source = new Imagick();
        $backgroundColor = new ImagickPixel($backgroundColor);
        $source->newImage($width, $height, $backgroundColor, self::MIME_PNG);
        $source = $source->getImage();
        if(!$returnSource)
        {
            $this->_source = $source;
            return $this;
        }
        else
        {
            return $source;
        }
    }

    /**
     * Drawing text to an image.
     *
     * @param string  $text      Text to draw.
     * @param integer $size      Font size.
     * @param integer $angle     Font drawing angle.
     * @param string  $color     Color for drawed text.
     * @param string  $font      Font file to use.
     * @param integer $leftSpace Margin from left.
     * @param integer $topSpace  Margin from top.

     *
     * @return Core_Image_Adapter_Imagic
     */
    public function drawText($text, $size, $angle, $color, $font, $leftSpace, $topSpace)
    {
        $this->_getSource();
        $draw = new ImagickDraw();
        $draw->setFont($font);
        $draw->setFontSize($size);
        $draw->setFillColor($color);
        $data = $this->_source->queryFontMetrics($draw, $text);
        $leftSpace = $leftSpace + ceil(abs($data['descender']) * sin(deg2rad($angle)));
        $topSpace = $topSpace + ceil(abs($data['ascender'] + $data['descender']) * cos(deg2rad($angle)));
        $this->_source->annotateImage($draw, $leftSpace, $topSpace, $angle, $text);
        return $this;
    }

    /**
     * Gets a size for rectangle rounding given text.
     *
     * @param float   $size  Font size in px.
     * @param integer $angle Font drawing angle.
     * @param string  $font  Font file to use.
     * @param string  $text  Text.
     *
     * @return stdClass
     */
    public function getTextSize($size, $angle, $font, $text)
    {
        $draw = new ImagickDraw();
        $draw->setFont($font);
        $draw->setFontSize($size);
        $draw->setFillColor('#999999');

        $im = new Imagick();
        $data = $im->queryFontMetrics($draw, $text);
        $out = new stdClass();
        $out->width = ceil(($data['textWidth'] * cos(deg2rad($angle))) + ($data['textHeight'] * sin(deg2rad($angle))));
        $out->width = $out->width + ($data['descender'] * sin(deg2rad($angle)));

        $out->height = ceil(($data['textWidth'] * sin(deg2rad($angle))) + ($data['textHeight'] * cos(deg2rad($angle))));
        $out->height = $out->height + ($data['descender'] * cos(deg2rad($angle)));
        return $out;
    }

    /**
     * Combining two images. Base Image will be enlarged if it will be needed.
     *
     * @param Core_Image_Adapter_Interface $source              Image to put above.
     * @param integer                      $left                Left space.
     * @param integer                      $top                 Top space.
     * @param integer                      $opacity             Opacity of putted image (0-100).
     * @param string                       $emptyAreaBackground Background for created image.
     *
     * @return Core_Image_Adapter_Gd
     */
    public function blend(Core_Image_Adapter_Interface $source, $left, $top, $opacity, $emptyAreaBackground)
    {
        $resultWidth = $this->getSize()->width;
        $resultHeight = $this->getSize()->height;
        $sourceSize = $source->getSize();
        if(($left + $sourceSize->width) > $resultWidth)
        {
            $resultWidth = $left + $sourceSize->width;
        }
        if(($top + $sourceSize->height) > $resultHeight)
        {
            $resultHeight = $top + $sourceSize->height;
        }

        $destinationResource = new Imagick();
        $emptyAreaBackground = new ImagickPixel($emptyAreaBackground);
        $destinationResource->newImage($resultWidth, $resultHeight, $emptyAreaBackground, self::MIME_PNG);
        $source = clone $source->_getSource();
        $source->setImageOpacity($opacity / 100);

        $destinationResource->compositeImage($this->_getSource(), imagick::COMPOSITE_DEFAULT, 0, 0);
        $this->_source->destroy();
        unset($this->_source);

        $destinationResource->compositeImage($source, imagick::COMPOSITE_DEFAULT, $left, $top);

        $this->_source = $destinationResource;
        return $this;
    }

    /**
     * Combining two images. Given image will be cropped if it goes beyond the basic.
     *
     * @param Core_Image_Adapter_Interface $image   Image to put above.
     * @param integer                      $left    Left space.
     * @param integer                      $top     Top space.
     * @param integer                      $opacity Opacity of putted image (0-100).
     *
     * @return Core_Image_Adapter_Imagick
     */
    public function combine(Core_Image_Adapter_Interface $image, $left, $top, $opacity)
    {
        $source = clone $image->_getSource();
        $source->setImageOpacity($opacity / 100);
        $this->_getSource()->compositeImage($source, imagick::COMPOSITE_DEFAULT, $left, $top);
        return $this;
    }

    /**
     * Image crop.
     *
     * @param integer $xTopLeft     Top left point X coordinate.
     * @param integer $yTopLeft     Top left point Y coordinate.
     * @param integer $xBottomRight Bottom right point X coordinate.
     * @param integer $yBottomRight Bottom right point Y coordinate.
     *
     * @return Core_Image_Adapter_Interface
     */
    public function crop($xTopLeft, $yTopLeft, $xBottomRight, $yBottomRight)
    {
        $width = ($xBottomRight - $xTopLeft);
        $height = ($yBottomRight - $yTopLeft);
        $this->_getSource()->cropImage($width, $height, $xTopLeft, $yTopLeft);
        return $this;
    }

    /**
     * Returns an adapter with resized image resource.
     *
     * @param integer $width  Width for resized image.
     * @param integer $height Height for resized image.
     *
     * @return Core_Image_Adapter_Gd
     */
    public function resize($width, $height)
    {
        $this->_getSource()->resizeImage($width, $height, imagick::FILTER_LANCZOS, 1);
        return $this;
    }

    /**
     * Gets size of loaded image source.
     *
     * @return stdClass
     */
    public function getSize()
    {
        $out = new stdClass();
        $out->width = $this
                ->_getSource()
                ->getImageWidth();
        $out->height = $this
                ->_getSource()
                ->getImageHeight();
        return $out;
    }

    /**
     * This function outputs rendered image to browser.
     *
     * @param string $mime Mime type of output image.
     *
     * @return void
     * @throws Core_Image_Exception Throws an error if mime fromat unsapported.
     */
    public function render($mime)
    {
        $source = $this->_getSource();
        if(!$source->setFormat($mime))
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('"' . $mime . '" is unsupported image type for Imagic library');
        }
        echo $source;
    }

    /**
     * Saves an image to file.
     *
     * @param string $path Path where image will be saved.
     * @param string $mime Mime type of saved image.
     *
     * @return boolean
     * @throws Core_Image_Exception If given mime type unsupported.
     */
    public function saveAs($path, $mime)
    {
        $result = false;
        $source = $this->_getSource();
        if(!$source->setFormat($mime))
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('"' . $mime . '" is unsupported image type for Imagic library');
        }
        $source->writeImage($path);
        return $result;
    }

    /**
     * This functions returns true if file already loaded to adapter.
     *
     * @abstract
     * @return boolean
     */
    public function isLoaded()
    {
        return ($this->_source != null);
    }

    /**
     * This function loading image from path.
     *
     * @param string $path Path to Image file.
     *
     * @return void
     */
    public function loadFromPath($path)
    {
        $this->_source = new Imagick($path);
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        $this->destroy();
    }

    /**
     * Destroyer.
     *
     * @return void
     */
    public function destroy()
    {
        if(!empty($this->_source))
        {
            $this->_source->destroy();
        }
    }
}