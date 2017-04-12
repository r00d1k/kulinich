<?php
/**
 * Adapter for php_GD graphics library for Core_Image.
 *
 * @class      Core_Image_Adapter_GD
 * @implements Core_Image_Adapter_Interface
 * @extends    Core_Image_Adapter_Abstract
 * @author     PMatvienko
 * @version    1.0
 * @package    Core_Image
 */
class Core_Image_Adapter_Gd extends Core_Image_Adapter_Abstract
{
    /**
     * Creates a new image resource for self.
     *
     * @param integer $width           New image width.
     * @param integer $height          New image height.
     * @param string  $backgroundColor Background color for text.
     * @param boolean $returnSource    Just create and return source.
     * 
     * @return Core_Image_Adapter_Gd
     */
    public function createNewResource($width, $height, $backgroundColor, $returnSource = false)
    {
        $created = imagecreatetruecolor($width, $height);

        imagealphablending($created, true);
        if($backgroundColor != null)
        {
            $bgColor = $this->_getColorResource($backgroundColor, 0, $created);
            imagefill($created, 0, 0, $bgColor);
        }
        if(!$returnSource)
        {
            if($this->_source != null)
            {
                imagedestroy($this->_source);
            }
            $this->_source = $created;

            return $this;
        }
        else
        {
            return $created;
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
     * @return Core_Image_Adapter_Gd
     */
    public function drawText($text, $size, $angle, $color, $font, $leftSpace, $topSpace)
    {
        $data = imagettfbbox($this->_px2pt($size), 0, $font, $text);
        $height = abs($data[3] - $data[7]);
        $descender = ceil($height * 0.22);
        $ascender = $height - $descender;

        imagettftext($this->_getSource(), $this->_px2pt($size), (-1 * $angle), ($leftSpace + ($descender * sin(deg2rad($angle)))), ($topSpace + ($ascender * cos(deg2rad($angle)))), $this->_getColorResource($color), $font, $text);

        return $this;
    }

    /**
     * Calculate points from pixels. Needed for correct drawing texts in GD2.
     *
     * @param integer $px Pixels.
     *
     * @return integer
     */
    protected function _px2pt($px)
    {
        $gdInfo = gd_info();
        if(stristr($gdInfo['GD Version'], '2.'))
        {
            return ceil($px * 72 / 96);
        }
        return $px;
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
        $data = imagettfbbox($this->_px2pt($size), 0, $font, $text);
        $out = new stdClass();
        $out->width = ceil(abs($data[2] - $data[6]) * cos(deg2rad($angle))) + ceil(abs($data[3] - $data[7]) * sin(deg2rad($angle)));
        $out->height = ceil(abs($data[2] - $data[6]) * sin(deg2rad($angle))) + ceil(abs($data[3] - $data[7]) * cos(deg2rad($angle)));
        return $out;
    }

    protected $_transparentColor = array(
        'red'   => 255,
        'green' => 255,
        'blue'  => 255,
        'alpha' => 127,
    );

    /**
     * Gets a resource by given color.
     *
     * @param string        $color          Color in format #RRGGBB.
     * @param integer       $opacity        Color opacity (0-127).
     * @param resource|null &$imageResource An image resource.
     *
     * @return integer
     */
    protected function _getColorResource($color, $opacity = 0, &$imageResource = null)
    {
        $colorResource = 0;
        if($imageResource == null)
        {
            $imageResource = $this->_getSource();
        }
        switch($color)
        {
            case self::COLOR_TRANSPARENT:
                $colorResource = imagecolorallocatealpha($imageResource, $this->_transparentColor['red'], $this->_transparentColor['green'], $this->_transparentColor['blue'], $this->_transparentColor['alpha']);
                $colorResource = imagecolortransparent($imageResource, $colorResource);
                break;

            default:
                $color = str_replace('#', '', $color);
                if(strlen($color) == 3)
                {
                    $colorRed = '0x' . str_repeat(substr($color, 0, 1), 2);
                    $colorGreen = '0x' . str_repeat(substr($color, 1, 1), 2);
                    $colorBlue = '0x' . str_repeat(substr($color, 2, 1), 2);
                }
                elseif(strlen($color) == 6)
                {
                    $colorRed = '0x' . substr($color, 0, 2);
                    $colorGreen = '0x' . substr($color, 2, 2);
                    $colorBlue = '0x' . substr($color, 4, 2);
                }
                if($opacity == 0)
                {
                    $colorResource = imagecolorallocate($imageResource, $colorRed, $colorGreen, $colorBlue);
                }
                else
                {
                    $colorResource = imagecolorallocatealpha($imageResource, $colorRed, $colorGreen, $colorBlue, $opacity);
                }
                break;
        }
        return $colorResource;
    }

    /**
     * Gets a color that set to transparent.
     *
     * @param resource|null &$source An image resource.
     * 
     * @return array
     */
    protected function _getTransparentColor(&$source = null)
    {
        if($source == null)
        {
            $source = $this->_getSource();
        }
        $transparencyIndex = imagecolortransparent($source);
        $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255, 'alpha' => 127);

        if($transparencyIndex >= 0)
        {
            $transparencyColor = imagecolorsforindex($source, $transparencyIndex);
        }
        return $transparencyColor;
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

        $destinationResource = $this->createNewResource($resultWidth, $resultHeight, $emptyAreaBackground, true);

        imagecopymerge($destinationResource, $this->_getSource(), 0, 0, 0, 0, $this->getSize()->width, $this->getSize()->height, 100);
        imagedestroy($this->_getSource());
        unset($this->_source);
        $this->_source = $destinationResource;

        imagecopymerge($this->_getSource(), $source->_getSource(), $left, $top, 0, 0, $sourceSize->width, $sourceSize->height, $opacity);

        return $this;
    }

    /**
     * Combining two images. Given image will be cropped if it goes beyond the basic.
     *
     * @param Core_Image_Adapter_Interface $source  Image to put above.
     * @param integer                      $left    Left space.
     * @param integer                      $top     Top space.
     * @param integer                      $opacity Opacity of putted image (0-100).
     *
     * @return Core_Image_Adapter_Gd
     */
    public function combine(Core_Image_Adapter_Interface $source, $left, $top, $opacity)
    {
        if($opacity < 0 || !is_numeric($opacity))
        {
            $opacity = 0;
        }
        if($opacity > 100)
        {
            $opacity = 100;
        }
        $opacity = intval($opacity);

        $sourceSize = $source->getSize();

        imagecopymerge($this->_getSource(), $source->_getSource(), $left, $top, 0, 0, $sourceSize->width, $sourceSize->height, $opacity);
        return $this;
    }

    public function grayscale()
    {
        imagefilter($this->_getSource(), IMG_FILTER_GRAYSCALE);
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

        $destinationResource = $this->createNewResource($width, $height, self::COLOR_TRANSPARENT, true);

        imagecopy($destinationResource, $this->_getSource(), 0, 0, $xTopLeft, $yTopLeft, $width, $height);

        imagedestroy($this->_source);
        $this->_source = $destinationResource;

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
        $originalSize = $this->getSize();
        $destinationResource = $this->createNewResource($width, $height, self::COLOR_TRANSPARENT, true);
        imagecolorallocate($destinationResource, 255, 255, 255);
        imagecopyresampled($destinationResource, $this->_getSource(), 0, 0, 0, 0, $width, $height, $originalSize->width, $originalSize->height);
        imagedestroy($this->_source);
        $this->_source = $destinationResource;

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
        $out->width = imagesx($this->_getSource());
        $out->height = imagesy($this->_getSource());
        return $out;
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
        imagealphablending($source, false);
        imagesavealpha($source, true);
        switch($mime)
        {
            case self::MIME_WBMP:
                $result = imagewbmp($source, $path);
                break;

            case self::MIME_GIF:
                $result = imagegif($source, $path);
                break;

            case self::MIME_JPEG:
                $result = imagejpeg($source, $path);
                break;

            case self::MIME_PNG:
                $result = imagepng($source, $path);
                break;

            default:
                Zend_Loader::loadClass('Core_Image_Exception');
                throw new Core_Image_Exception('"' . $mime . '" is unsupported image type for GD library');
                break;
        }
        return $result;
    }

    /**
     * This function outputs rendered image to browser.
     *
     * @param string $mime Mime type of output image.
     *
     * @return boolean.
     * @throws Core_Image_Exception Throws an error if mime fromat unsapported.
     */
    public function render($mime = self::MIME_PNG)
    {
        $result = false;
        $source = $this->_getSource();
        imagealphablending($source, false);
        imagesavealpha($source, true);
        switch($mime)
        {
            case self::MIME_WBMP:
                $result = imagewbmp($source);
                break;

            case self::MIME_GIF:
                $result = imagegif($source);
                break;

            case self::MIME_JPEG:
                $result = imagejpeg($source);
                break;

            case self::MIME_PNG:
                $result = imagepng($source);
                break;

            default:
                Zend_Loader::loadClass('Core_Image_Exception');
                throw new Core_Image_Exception('"' . $mime . '" is unsupported image type for GD library');
                break;
        }
        return $result;
    }

    /**
     * This functions returns true if file already loaded to adapter.
     *
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
     * @return Core_Image_Adapter_Gd
     *
     * @throws Core_Image_Exception Throws zend exception if mime type is unsupported.
     */
    public function loadFromPath($path)
    {
        $mime = $this->_getFileMimeType($path);
        $mime = explode('/', $mime);
        switch($mime[1])
        {
            case self::MIME_WBMP:
                $this->_source = imagecreatefromwbmp($path);
                break;

            case self::MIME_BMP:
                $this->_source = $this->_imageCreateFromMsBmp($path);
                break;

            case self::MIME_GIF:
                $this->_source = imagecreatefromgif($path);
                break;

            case self::MIME_JPEG:
                $this->_source = imagecreatefromjpeg($path);
                break;

            case self::MIME_PNG:
                $this->_source = imagecreatefrompng($path);
                break;

            default:
                throw Core_Image_Exception('"' . $mime[1] . '" is unsupported image type for GD library');
                break;
        }
        imagealphablending($this->_source, true);
        //imagesavealpha($this->_source, true);
        return $this;
    }

    /**
     * This function need to add ms-bmp support.
     *
     * @param string $filename Path to a file.
     *
     * @return boolean|resource
     */
    protected function _imageCreateFromMsBmp($filename)
    {
        if(!$fCursor = fopen($filename, "rb"))
        {
            return false;
        }
        $file = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($fCursor, 14));
        if($file['file_type'] != 19778)
        {
            return false;
        }
        $bmp = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vsize_bitmap/Vhoriz_resolution/Vvert_resolution/Vcolors_used/Vcolors_important', fread($fCursor, 40));
        $bmp['colors'] = pow(2, $bmp['bits_per_pixel']);
        if($bmp['size_bitmap'] == 0)
        {
            $bmp['size_bitmap'] = $file['file_size'] - $file['bitmap_offset'];
        }
        $bmp['bytes_per_pixel'] = $bmp['bits_per_pixel'] / 8;
        $bmp['bytes_per_pixel2'] = ceil($bmp['bytes_per_pixel']);
        $bmp['decal'] = ($bmp['width'] * $bmp['bytes_per_pixel'] / 4);
        $bmp['decal'] -= floor($bmp['width'] * $bmp['bytes_per_pixel'] / 4);
        $bmp['decal'] = 4 - (4 * $bmp['decal']);
        if($bmp['decal'] == 4)
        {
            $bmp['decal'] = 0;
        }
        $palette =
                array
                ();
        if($bmp['colors'] < 16777216)
        {
            $palette = unpack('V' . $bmp['colors'], fread($fCursor, $bmp['colors'] * 4));
        }
        $img = fread($fCursor, $bmp['size_bitmap']);
        $vide = chr(0);
        $res = imagecreatetruecolor($bmp['width'], $bmp['height']);
        $p = 0;
        $y = $bmp['height'] - 1;
        while($y >= 0)
        {
            $x = 0;
            while($x < $bmp['width'])
            {
                if($bmp['bits_per_pixel'] == 24)
                {
                    $color = unpack("V", substr($img, $p, 3) . $vide);
                }
                elseif($bmp['bits_per_pixel'] == 16)
                {
                    $color = unpack("n", substr($img, $p, 2));
                    $color[1] = $palette[$color[1] + 1];
                }
                elseif($bmp['bits_per_pixel'] == 8)
                {
                    $color = unpack("n", $vide . substr($img, $p, 1));
                    $color[1] = $palette[$color[1] + 1];
                }
                elseif($bmp['bits_per_pixel'] == 4)
                {
                    $color = unpack("n", $vide . substr($img, floor($p), 1));
                    if(($p * 2) % 2 == 0)
                    {
                        $color[1] = ($color[1] >> 4);
                    }
                    else
                    {
                        $color[1] = ($color[1] & 0x0F);
                    }
                    $color[1] = $palette[$color[1] + 1];
                }
                elseif($bmp['bits_per_pixel'] == 1)
                {
                    $color = unpack("n", $vide . substr($img, floor($p), 1));
                    if(($p * 8) % 8 == 0)
                    {
                        $color[1] = $color[1] >> 7;
                    }
                    elseif(($p * 8) % 8 == 1)
                    {
                        $color[1] = ($color[1] & 0x40) >> 6;
                    }
                    elseif(($p * 8) % 8 == 2)
                    {
                        $color[1] = ($color[1] & 0x20) >> 5;
                    }
                    elseif(($p * 8) % 8 == 3)
                    {
                        $color[1] = ($color[1] & 0x10) >> 4;
                    }
                    elseif(($p * 8) % 8 == 4)
                    {
                        $color[1] = ($color[1] & 0x8) >> 3;
                    }
                    elseif(($p * 8) % 8 == 5)
                    {
                        $color[1] = ($color[1] & 0x4) >> 2;
                    }
                    elseif(($p * 8) % 8 == 6)
                    {
                        $color[1] = ($color[1] & 0x2) >> 1;
                    }
                    elseif(($p * 8) % 8 == 7)
                    {
                        $color[1] = ($color[1] & 0x1);
                    }
                    $color[1] = $palette[$color[1] + 1];
                }
                else
                {
                    return false;
                }
                imagesetpixel($res, $x, $y, $color[1]);
                $x++;
                $p += $bmp['bytes_per_pixel'];
            }
            $y--;
            $p += $bmp['decal'];
        }
        fclose($fCursor);
        return $res;
    }

    /**
     *  Destructor. 
     */
    public function __destruct()
    {
        if($this->_source != null)
        {
            imagedestroy($this->_source);
        }
    }
}