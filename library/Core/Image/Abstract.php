<?php
/**
 * Core_Image_Abstract Base functionality for Core_Image.
 *
 * @abstract
 * @class
 * @author  PMatvienko
 * @version 1.0
 * @package Core_Image
 * @todo    Extend functionality
 */
abstract class Core_Image_Abstract
{
    const RESIZE_FIT_SMALLER = 'fit_smaller';

    const RESIZE_FIT_BIGGER = 'fit_bigger';

    const RESIZE_CROP = 'resize_crop';

    const RESIZE_NATURAL = 'natural';

    const POSITION_TOP = 'top';

    const POSITION_BOTTOM = 'bottom';

    const POSITION_LEFT = 'left';

    const POSITION_RIGHT = 'right';

    const POSITION_CENTER = 'center';

    /**
     * @var Core_Image_Adapter_Cairo|Core_Image_Adapter_Gd|Core_Image_Adapter_Gmagick|Core_Image_Adapter_Imagick|null
     */
    protected $_source = null;

    /**
     * @var Core_Image_Adapter_Cairo|Core_Image_Adapter_Gd|Core_Image_Adapter_Gmagick|Core_Image_Adapter_Imagick|null
     */
    protected static $_adapter = null;

    /**
     * Class Constructor.
     *
     * @param null|string|Core_Vfs_File               $imageSource An imageSource can be path to a file or serialized resource or null.
     * @param null|string|Core_Image_Adapter_Abstract $adapter     Graphics Adapter class name Or an Adapter class instance or null to use autodetected adapter.
     *
     * @throws Core_Image_Exception On error.
     */
    public function __construct($imageSource = null, $adapter = null)
    {
        if($adapter != null)
        {
            if(is_string($adapter))
            {
                $adapter = new $adapter();
            }
            $this->_source = $adapter;
        }
        if($imageSource != null)
        {
            $this->loadFromFile($imageSource);
        }
    }

    /**
     * Draws text on image.
     *
     * @param string       $text      Text to draw.
     * @param integer      $leftSpace Left Margin.
     * @param integer      $topSpace  Top Margin.
     * @param null|integer $size      Text Size.
     * @param null|integer $angle     Draw Angle.
     * @param null|string  $color     Color.
     * @param null|string  $font      Font to use.
     *
     * @return Core_Image
     * @throws Core_Image_Exception On error.
     */
    public function annotate($text, $leftSpace = 0, $topSpace = 0, $size = null, $angle = null, $color = null, $font = null)
    {
        $config = Core_Image_Text::getDefaultConfig();
        $fontPath = $config['fontsDirectory'];
        if($size == null)
        {
            $size = $config['fontSize'];
        }
        if($angle == null)
        {
            $angle = $config['angle'];
        }
        if($color == null)
        {
            $color = $config['color'];
        }
        if($font == null)
        {
            $font = $config['font'];
        }

        if(empty($fontPath))
        {
            $fontPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Text' . DIRECTORY_SEPARATOR . 'Fonts' . DIRECTORY_SEPARATOR;
        }
        if(!stristr($font, '/') && !stristr($font, '\\'))
        {
            if(strtolower(strrchr($font, '.')) != '.ttf')
            {
                $font .= '.ttf';
            }
            $font = $fontPath . $font;
        }
        if(!file_exists($font))
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Font file "' . $font . '" not found.');
        }
        $this->_getSource()->drawText($text, $size, $angle, $color, $font, $leftSpace, $topSpace);
        return $this;
    }

    /**
     * Combining two images. Base Image will be enlarged if it will be needed. Be Careful with this function it can Eat all your php's memory!!!.
     *
     * @param Core_Image_Abstract $image       Image to combine with.
     * @param integer|string      $xLeft       Left space.
     * @param integer|string      $yTop        Top space.
     * @param integer             $opacity     Copied image opacity 0 - 100.
     * @param string              $emptyAreaBg Background color for new image.
     *
     * @return Core_Image_Abstract
     * @throws Core_Image_Exception On error.
     */
    public function blend(Core_Image_Abstract $image, $xLeft = self::POSITION_LEFT, $yTop = self::POSITION_TOP, $opacity = 100, $emptyAreaBg = Core_Image_Adapter_Interface::COLOR_TRANSPARENT)
    {
        $image = $image->_getSource();
        if(get_class($image) != get_class($this->_getSource()))
        {
            $adapterToUse = get_class($this->_getSource());

            $tempFile = tempnam(sys_get_temp_dir(), 'Core_Image_Export');
            $image->saveAs($tempFile, Core_Image_Adapter_Interface::MIME_PNG);

            $image = new $adapterToUse();
            $image->loadFromPath($tempFile);
            unlink($tempFile);
        }

        if($xLeft == self::POSITION_LEFT)
        {
            $xLeft = 0;
        }
        elseif($xLeft == self::POSITION_RIGHT)
        {
            $xLeft = ($this->getWidth() - $image->getSize()->width);
        }
        elseif($xLeft == self::POSITION_CENTER)
        {
            $xLeft = intval(($this->getWidth() - $image->getSize()->width) / 2);
        }

        if($yTop == self::POSITION_TOP)
        {
            $yTop = 0;
        }
        elseif($yTop == self::POSITION_BOTTOM)
        {
            $yTop = ($this->getHeight() - $image->getSize()->height);
        }
        elseif($yTop == self::POSITION_CENTER)
        {
            $yTop = intval(($this->getHeight() - $image->getSize()->height) / 2);
        }

        if(!is_numeric($xLeft) || !is_numeric($yTop))
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Incorrect coordinates X:"' . $xLeft . '", Y:"' . $yTop . '"');
        }

        if($xLeft < 0)
        {
            $xLeft = ($this->getWidth() - $image->getSize()->width + $xLeft);
        }
        if($yTop < 0)
        {
            $yTop = ($this->getHeight() - $image->getSize()->height + $yTop);
        }

        $this
            ->_getSource()
            ->blend($image, $xLeft, $yTop, $opacity, $emptyAreaBg);
        return $this;
    }

    /**
     * Combining two images. Given image will be cropped if it goes beyond the basic.
     *
     * @param Core_Image_Abstract $image   Image to combine with.
     * @param integer|string      $xLeft   Left space.
     * @param integer|string      $yTop    Top space.
     * @param integer             $opacity Copied image opacity 0 - 100.
     *
     * @return Core_Image_Abstract
     * @throws Core_Image_Exception On error.
     */
    public function combine(Core_Image_Abstract $image, $xLeft = self::POSITION_LEFT, $yTop = self::POSITION_TOP, $opacity = 100)
    {
        $image = $image->_getSource();
        if(get_class($image) != get_class($this->_getSource()))
        {
            $adapterToUse = get_class($this->_getSource());

            $tempFile = tempnam(sys_get_temp_dir(), 'Core_Image_Export');
            $image->saveAs($tempFile, Core_Image_Adapter_Interface::MIME_PNG);

            $image = new $adapterToUse();
            $image->loadFromPath($tempFile);
            unlink($tempFile);
        }

        if($xLeft == self::POSITION_LEFT)
        {
            $xLeft = 0;
        }
        elseif($xLeft == self::POSITION_RIGHT)
        {
            $xLeft = ($this->getWidth() - $image->getSize()->width);
        }
        elseif($xLeft == self::POSITION_CENTER)
        {
            $xLeft = intval(($this->getWidth() - $image->getSize()->width) / 2);
        }

        if($yTop == self::POSITION_TOP)
        {
            $yTop = 0;
        }
        elseif($yTop == self::POSITION_BOTTOM)
        {
            $yTop = ($this->getHeight() - $image->getSize()->height);
        }
        elseif($yTop == self::POSITION_CENTER)
        {
            $yTop = intval(($this->getHeight() - $image->getSize()->height) / 2);
        }

        if(!is_numeric($xLeft) || !is_numeric($yTop))
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Incorrect coordinates X:"' . $xLeft . '", Y:"' . $yTop . '"');
        }

        if($xLeft < 0)
        {
            $xLeft = ($this->getWidth() - $image->getSize()->width + $xLeft);
        }
        if($yTop < 0)
        {
            $yTop = ($this->getHeight() - $image->getSize()->height + $yTop);
        }

        $this
            ->_getSource()
            ->combine($image, $xLeft, $yTop, $opacity);
        return $this;
    }

    /**
     * Gets a part of image and saves it as a resource in current Core_Image instance.
     *
     * @param integer $xTopLeft     Top left point X coordinate.
     * @param integer $yTopLeft     Top left point Y coordinate.
     * @param integer $xBottomRight Bottom Right point X coordinate.
     * @param integer $yBottomRight Bottom Right point Y coordinate.
     *
     * @return boolean|Core_Image
     * @throws Core_Image_Exception On error.
     */
    public function crop($xTopLeft, $yTopLeft, $xBottomRight, $yBottomRight)
    {
        $size = $this->getSize();
        if($size->width < $xBottomRight || $size->height < $yBottomRight || $xTopLeft < 0 || $yTopLeft < 0)
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Crop coordinates are out of image');
        }
        elseif($xTopLeft == $xBottomRight || $yTopLeft == $yBottomRight)
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Size Of cropped image must be grater that "0". Current height:"'. ($yTopLeft - $yBottomRight) .'" Current width:"'. ($xTopLeft - $xBottomRight) .'"');
        }
        $this
            ->_getSource()
            ->crop($xTopLeft, $yTopLeft, $xBottomRight, $yBottomRight);
        return $this;
    }

    /**
     * Resize Current Image Resource.
     *
     * @param integer $width      Width.
     * @param integer $height     Height.
     * @param string  $resizeType Type of resize action.
     *
     * @return boolean|Core_Image_Abstract;
     */
    public function resize($width, $height, $resizeType = self::RESIZE_NATURAL)
    {
        switch($resizeType)
        {
            case self::RESIZE_FIT_BIGGER:
                $result = $this
                        ->_getSource()
                        ->resizeFitBigger($width, $height);
                break;

            case self::RESIZE_FIT_SMALLER:
                $result = $this
                        ->_getSource()
                        ->resizeFitSmaller($width, $height);
                break;

            case self::RESIZE_CROP:
                $result = $this
                        ->_getSource()
                        ->resizeWithCrop($width, $height);
                break;

            default:
                $result = $this
                        ->_getSource()
                        ->resize($width, $height);
                break;
        }
        return $this;
    }

    public function grayscale()
    {
        $result = $this->_getSource()->grayscale();
        return $this;
    }

    /**
     * Gets an image width.
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->getSize()->width;
    }

    /**
     * Gets an image height.
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->getSize()->height;
    }

    /**
     * Gets size of loaded image source.
     *
     * @return stdClass
     */
    public function getSize()
    {
        return $this
                ->_getSource()
                ->getSize();
    }

    /**
     * This function outputs image to browser.
     *
     * @param string $mime Mime type of outputed file.
     *
     * @return boolean
     * @throws Core_Image_Exception On error.
     */
    public function render($mime = Core_Image_Adapter_Interface::MIME_JPEG)
    {
        header('Content-type: image/' . $mime);
        return $this
                ->_getSource()
                ->render($mime);
    }

    /**
     * Saving image to a file.
     *
     * @param string      $path            Path to save image to.
     * @param null|string $mime            Optional. Mime type of saved image.
     * @param boolean     $forcedOwerwrite Owerwrite file if exists.
     *
     * @return boolean
     * @throws Core_Image_Exception If file exists, or myme type not given and not detected.
     */
    public function save($path, $mime = null, $forcedOwerwrite = false)
    {
        if(file_exists($path))
        {
            if(!$forcedOwerwrite)
            {
                Zend_Loader::loadClass('Core_Image_Exception');
                throw new Core_Image_Exception('File "' . $path . '"');
            }
            else
            {
                unlink($path);
            }
        }
        if($mime == null)
        {
            $mime = $this->_detectMimeByPath($path);
        }
        if($mime == null)
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('File mime type not defined');
        }
        return $this
                ->_getSource()
                ->saveAs($path, $mime);
    }

    /**
     * Detecting myme type for image by extension in given path.
     *
     * @param string $path Path to save image to.
     *
     * @return null|string
     */
    protected function _detectMimeByPath($path)
    {
        $path = substr(strrchr($path, '.'), 1);
        if(in_array($path, array(Core_Image_Adapter_Interface::MIME_GIF, Core_Image_Adapter_Interface::MIME_JPEG, Core_Image_Adapter_Interface::MIME_PNG)))
        {
            return $path;
        }
        if($path == 'jpg')
        {
            return Core_Image_Adapter_Interface::MIME_JPEG;
        }
        return null;
    }

    /**
     * This function returns a source adapter.
     *
     * @return Core_Image_Adapter_Abstract|Core_Image_Adapter_Cairo|Core_Image_Adapter_Gd|Core_Image_Adapter_Gmagick|Core_Image_Adapter_Imagick|null|string
     * @throws Core_Image_Exception Throws Core_Image_Exception if image not set.
     */
    protected function _getSource()
    {
        if($this->_source == null || !$this->_source->isLoaded())
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Image not set.');
        }
        return $this->_source;
    }

    /**
     * Loading image from file.
     *
     * @param string $imageSource Path to image file.
     *
     * @return Core_Image_Abstract
     * @throws Core_Image_Exception Throws an exception if file not found.
     */
    public function loadFromFile($imageSource)
    {
        if($imageSource instanceof Core_Vfs_File)
        {
            if($imageSource->isInLocalFs())
            {
                $imageSource = $imageSource->getLocalPath();
            }
            else
            {
                $temp = $imageSource->copyTo(new Core_Vfs_Dir(sys_get_temp_dir()));
                $imageSource = $temp->getLocalPath();
            }
        }
        if(!file_exists($imageSource))
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Image file not found at location "' . $imageSource . '"');
        }
        if($this->_source == null)
        {
            $this->_source = self::getDefaultGraphicAdapter();
        }
        if($this->_source == null)
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Can not detect valid graphics adapter to process image');
        }
        if($this->_source->isLoaded())
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('This Core_Image already have a loaded file');
        }
        $this->_source->loadFromPath($imageSource);

        if(isset($temp))
        {
            $temp->remove();
        }
        return $this;
    }

    /**
     * Returns a graphic adapter to use.
     *
     * @return Core_Image_Adapter_Cairo|Core_Image_Adapter_Gd|Core_Image_Adapter_Gmagick|Core_Image_Adapter_Imagick|null
     */
    public static function getDefaultGraphicAdapter()
    {
        if(self::$_adapter == null)
        {
            self::setDefaultGraphicAdapter();
        }
        $adapter = clone self::$_adapter;
        return $adapter;
    }

    /**
     * This method used to set a Graphic adapter.
     *
     * @param null|string|Core_Image_Adapter_Abstract $adapter Graphics Adapter class name Or an Adapter class instance or null to use autodetected adapter.
     *
     * @return void
     * @throws Core_Image_Exception   Throw an exception if no Graphic libraries available.
     */
    public static function setDefaultGraphicAdapter($adapter = null)
    {
        if($adapter == null)
        {
            $adapter = self::_detectAdapter();
        }
        elseif(is_string($adapter))
        {
            $adapter = new $adapter();
        }
        if($adapter === null)
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Graphic adapter not provided');
        }
        self::$_adapter = $adapter;
    }

    /**
     * This method detects installed Graphic libraries and returns Adapter for it.
     *
     * @return Core_Image_Adapter_Cairo|Core_Image_Adapter_Gd|Core_Image_Adapter_Gmagick|Core_Image_Adapter_Imagick|null
     */
    protected static function _detectAdapter()
    {
        if(function_exists('gd_info'))
        {
            return new Core_Image_Adapter_Gd();
        }
        else
        {
            if(class_exists('Imagick'))
            {
                return new Core_Image_Adapter_Imagick();
            }
            else
            {
                if(class_exists('Gmagick'))
                {
                    return new Core_Image_Adapter_Gmagick();
                }
                else
                {
                    if(class_exists('Cairo'))
                    {
                        return new Core_Image_Adapter_Cairo();
                    }
                }
            }
        }
        return null;
    }

    /**
     *  Destructor.
     */
    public function __destruct()
    {
        $this->destroy();
    }

    /**
     *  Destructor.
     *
     * @return void
     */
    public function destroy()
    {
        if($this->_source instanceof Core_Image_Adapter_Interface)
        {
            unset($this->_source);
        }
    }
}