<?php
/**
 * This Class needed to work with text like with images. Supported all methods from Core_Image_Abstract, but that methods will return an instance of Core_Image.
 *
 * @class   Core_Image_text
 * @author  PMatvienko
 * @version 1.0
 * @package Core_Image
 * @todo    Extend functionality
 */
class Core_Image_Text extends Core_Image_Abstract
{
    protected $_fontsDirectory = null;

    protected $_text = null;

    protected $_font = null;

    protected $_fontSize = null;

    protected $_color = null;

    protected $_angle = null;

    protected $_backgroundColor = null;

    protected static $_defultConfig = array
    (
        'angle'           => 0,
        'color'           => '#000',
        'backgroundColor' => Core_Image_Adapter_Interface::COLOR_TRANSPARENT,
        'fontSize'        => 20,
        'font'            => 'tahoma',
        'fontsDirectory'  => null,
    );

    /**
     * Class Constructor.
     *
     * @param mixed                                   $data    Text to write or, array of settings or instance of Zend_Config object with settings.
     * @param null|string|Core_Image_Adapter_Abstract $adapter Graphics Adapter class name Or an Adapter class instance or null to use autodetected adapter.
     *
     * @throws Core_Image_Exception On error.
     */
    public function __construct($data = null, $adapter = null)
    {
        if($adapter != null)
        {
            if(is_string($adapter))
            {
                $adapter = new $adapter();
            }
            $this->_source = $adapter;
        }

        $this->setFontsDirectory();

        $defaultConfig = self::getDefaultConfig();
        if($defaultConfig != null)
        {
            $this->setConfig($defaultConfig);
        }

        if(is_array($data) || $data instanceof Zend_Config)
        {
            $this->setConfig($data);
        }
        if(is_string($data))
        {
            $this->_text = $data;
        }
    }

    /**
     * This function returns a source adapter.
     *
     * @return Core_Image_Adapter_Interface
     * @throws Core_Image_Exception Throws Core_Image_Exception if image not set.
     */
    protected function _getSource()
    {
        if($this->_source == null)
        {
            $this->_source = self::getDefaultGraphicAdapter();
        }
        $textSize = $this->_source->getTextSize($this->_fontSize, $this->_angle, $this->_font, $this->_text);
        $this->_source
                ->createNewResource($textSize->width, $textSize->height, $this->_backgroundColor)
                ->drawText($this->_text, $this->_fontSize, $this->_angle, $this->_color, $this->_font, 0, 0);
        return $this->_source;
    }

    /**
     * Sets a drawing angle for text.
     *
     * @param integer $angle Angle.
     *
     * @return Core_Image_Text
     */
    public function setAngle($angle)
    {
        $this->_angle = $angle;
        return $this;
    }

    /**
     * Sets a background color for text image.
     *
     * @param string $color Color.
     *
     * @return Core_Image_Text
     */
    public function setBackgroundColor($color)
    {
        $this->_backgroundColor = $color;
        return $this;
    }

    /**
     * Sets a text color.
     *
     * @param string $color Color.
     *
     * @return Core_Image_Text
     */
    public function setColor($color)
    {
        $this->_color = $color;
        return $this;
    }

    /**
     * Sets a font size.
     *
     * @param integer $size Font size.
     *
     * @return Core_Image_Text
     */
    public function setFontSize($size)
    {
        $this->_fontSize = $size;
        return $this;
    }

    /**
     * Sets a font file.
     *
     * @param string $font File name or absolute path to font file.
     *
     * @return Core_Image_Text
     * @throws Core_Image_Exception If font file not found.
     */
    public function setFont($font)
    {
        if(!stristr($font, '/') && !stristr($font, '\\'))
        {
            if(strtolower(strrchr($font, '.')) != '.ttf')
            {
                $font .= '.ttf';
            }
            $font = $this->_fontsDirectory . $font;
        }
        if(!file_exists($font))
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Font file "' . $font . '" not found.');
        }
        $this->_font = $font;
        return $this;
    }

    /**
     * Sets a text to draw.
     *
     * @param string $text Text.
     *
     * @return Core_Image_Text
     */
    public function setText($text)
    {
        $this->_text = $text;
        return $this;
    }

    /**
     * Sets a path to a directory where font files are located.
     *
     * @param string $path Path to a directory.
     *
     * @return Core_Image_Text
     */
    public function setFontsDirectory($path = null)
    {
        if($path == null)
        {
            $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Text' . DIRECTORY_SEPARATOR . 'Fonts' . DIRECTORY_SEPARATOR;
        }
        elseif(substr($path, -1) != '/' && substr($path, -1) != '\\')
        {
            $path .= DIRECTORY_SEPARATOR;
        }
        $this->_fontsDirectory = $path;
        return $this;
    }

    /**
     * Sets a config for current object instance.
     *
     * @param Zend_Config|array $config Config in format array('angle'=>0,'color'=>'#000','backgroundColor'=>'transparent','fontSize'=> 20,'font'=>'tahoma').
     *
     * @return Core_Image_text.
     */
    public function setConfig($config)
    {
        foreach($config as $param => $value)
        {
            $setMethodName = 'set' . ucfirst($param);
            if(method_exists($this, $setMethodName))
            {
                $this->$setMethodName($value);
            }
        }
        return $this;
    }

    /**
     * Gets a default global config.
     *
     * @static
     * @return array|Zend_Config
     */
    public static function getDefaultConfig()
    {
        return self::$_defultConfig;
    }

    /**
     * Sets a default global config.
     *
     * @param Zend_Config|array $config Config in format array('angle'=>0,'color'=>'#000','backgroundColor'=>'transparent','fontSize'=> 20,'font'=>'tahoma').
     *
     * @return void
     * @throws Core_Image_Exception If config not an instance of Zend_Config and not array.
     */
    public static function setDefaultConfig($config)
    {
        if(!is_array($config) && !($config instanceof Zend_Config))
        {
            Zend_Loader::loadClass('Core_Image_Exception');
            throw new Core_Image_Exception('Core_Image_Text config must be an instance of Zend_Config or an array.');
        }
        self::$_defultConfig = $config;
    }
}