<?php
/**
 * Adapter interface for Core_Image adapters.
 *
 * @class   Core_Image_Adapter_Interface
 * @author  PMatvienko
 * @version 1.0
 * @package Core_Image
 */
interface Core_Image_Adapter_Interface
{
    const MIME_BMP = 'x-ms-bmp';

    const MIME_WBMP = 'vnd.wap.wbmp';

    const MIME_GIF = 'gif';

    const MIME_JPEG = 'jpeg';

    const MIME_PNG = 'png';

    const COLOR_TRANSPARENT = 'transparent';

    /**
     * Creates a new image resource for self.
     *
     * @param integer $width           New image width.
     * @param integer $height          New image height.
     * @param string  $backgroundColor Background color for text.
     * @param boolean $returnSource    Just create and return source.
     *
     * @return Core_Image_Adapter_Interface
     */
    public function createNewResource($width, $height, $backgroundColor, $returnSource = false);

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
     * @return Core_Image_Adapter_Interface
     */
    public function drawText($text, $size, $angle, $color, $font, $leftSpace, $topSpace);

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
    public function getTextSize($size, $angle, $font, $text);

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
    public function blend(Core_Image_Adapter_Interface $source, $left, $top, $opacity, $emptyAreaBackground);

    /**
     * Combining two images. Given image will be cropped if it goes beyond the basic.
     *
     * @param Core_Image_Adapter_Interface $source  Image to put above.
     * @param integer                      $left    Left space.
     * @param integer                      $top     Top space.
     * @param integer                      $opacity Opacity of putted image.
     *
     * @return Core_Image_Adapter_Interface
     */
    public function combine(Core_Image_Adapter_Interface $source, $left, $top, $opacity);

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
    public function crop($xTopLeft, $yTopLeft, $xBottomRight, $yBottomRight);
    /**
     * Gets an adapter with resized image resource.
     *
     * @param integer $width  Width for resized image.
     * @param integer $height Height for resized image.
     *
     * @abstract
     * @return Core_Image_Adapter_Interface
     */
    public function resize($width, $height);

    /**
     * Gets an adapter with Fitted by smaller side ratio image resource.
     *
     * @param integer $width  Width for resized image.
     * @param integer $height Height for resized image.
     *
     * @abstract
     * @return Core_Image_Adapter_Interface
     */
    public function resizeFitSmaller($width, $height);

    /**
     * Gets an adapter with Fitted by bigger side ratio image resource.
     *
     * @param integer $width  Width for resized image.
     * @param integer $height Height for resized image.
     *
     * @abstract
     * @return Core_Image_Adapter_Interface
     */
    public function resizeFitBigger($width, $height);

    /**
     * Gets an adapter with image resource that was resized by bigger ratio and cropped to given size.
     *
     * @param integer $width  Width for resized image.
     * @param integer $height Height for resized image.
     *
     * @abstract
     * @return Core_Image_Adapter_Interface
     */
    public function resizeWithCrop($width, $height);

    /**
     * Gets size of loaded image source.
     *
     * @abstract
     * @return stdClass
     */
    public function getSize();

    /**
     * Saves an image to file.
     *
     * @param string $path Path where image will be saved.
     * @param string $mime Mime type of saved image.
     *
     * @return boolean
     * @throws Zend_Exception If given mime type unsupported.
     */
    public function saveAs($path, $mime);

    /**
     * This function outputs rendered image to browser.
     *
     * @param string $mime Mime type of output image.
     *
     * @abstract
     * @return boolean.
     */
    public function render($mime);
    /**
     * This function loading image from path.
     *
     * @param string $path Path to Image file.
     *
     * @return void
     */

    public function loadFromPath($path);

    /**
     * This functions returns true if file already loaded to adapter.
     *
     * @abstract
     * @return boolean
     */
    public function isLoaded();
}