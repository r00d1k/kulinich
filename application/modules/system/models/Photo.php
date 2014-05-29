<?php
class System_Model_Photo extends Core_Entity_Model_Abstract
{
    public function save()
    {
        $assetsLocation = PUBLIC_PATH.'/assets/photos/';
        if(!is_dir($assetsLocation))
        {
            mkdir($assetsLocation,0777,true);
            chmod($assetsLocation, 0777);
        }
        if(!is_dir($assetsLocation.'thumb'))
        {
            mkdir($assetsLocation.'thumb',0777,true);
            chmod($assetsLocation.'thumb', 0777);
        }
        if(!is_dir($assetsLocation.'large'))
        {
            mkdir($assetsLocation.'large',0777,true);
            chmod($assetsLocation.'large', 0777);
        }
        if($this->image != null && stristr($this->image, 'tmp') && is_file(PUBLIC_PATH . $this->image))
        {
            $newName = uniqid('image_').'.png';
            Core_Image::factory(PUBLIC_PATH . $this->image)
                ->resize(1366,768, Core_Image::RESIZE_FIT_SMALLER)
                ->save($assetsLocation . 'large/' . $newName);
            Core_Image::factory(PUBLIC_PATH . $this->image)
                ->resize(480,320, Core_Image::RESIZE_CROP)
                ->save($assetsLocation.$newName);
            Core_Image::factory(PUBLIC_PATH . $this->image)
                ->resize(137,92, Core_Image::RESIZE_CROP)
                ->save($assetsLocation . 'thumb/' . $newName);

            unlink(PUBLIC_PATH . $this->image);

            if(!empty($this->_dataOriginal['image']) && is_file(PUBLIC_PATH . $this->_dataOriginal['image']))
            {
                unlink(PUBLIC_PATH . $this->_dataOriginal['image']);
                unlink(PUBLIC_PATH . str_replace('photos/','photos/thumb/',$this->_dataOriginal['image']));
                unlink(PUBLIC_PATH . str_replace('photos/','photos/large/',$this->_dataOriginal['image']));
            }
            $this->image = '/assets/photos/'.$newName;
        }
        elseif($this->image != null && !file_exists(PUBLIC_PATH . $this->image))
        {
            $this->image = null;
        }
        return parent::save();
    }

    public function delete()
    {
        if($this->image != null && is_file(PUBLIC_PATH . $this->image))
        {
            unlink(PUBLIC_PATH . $this->image);
            unlink(PUBLIC_PATH . str_replace('photos/','photos/thumb/',$this->image));
            unlink(PUBLIC_PATH . str_replace('photos/','photos/large/',$this->image));
        }
        return parent::delete();
    }

    public function __get($name)
    {
        switch($name)
        {
            case 'imageThumb':
                return str_replace('photos/','photos/thumb/',$this->image);
                break;

            case 'imageLarge':
                return str_replace('photos/','photos/large/',$this->image);
                break;

            default:
                return parent::__get($name);
        }
    }
}