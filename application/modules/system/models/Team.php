<?php
class System_Model_Team extends Core_Entity_Model_Abstract
{
    public function save()
    {
        $assetsLocation = PUBLIC_PATH.'/assets/team/';
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
        if($this->image != null && stristr($this->image, 'tmp') && is_file(PUBLIC_PATH . $this->image))
        {
            $newName = uniqid('image_').'.png';
            $image = Core_Image::factory(PUBLIC_PATH . $this->image)
                ->resize(309,309, Core_Image::RESIZE_CROP);
            $image->save($assetsLocation.$newName);

            $image
                ->grayscale()
                ->save($assetsLocation . 'thumb/' . $newName);

            unlink(PUBLIC_PATH . $this->image);

            if(!empty($this->_dataOriginal['image']) && is_file(PUBLIC_PATH . $this->_dataOriginal['image']))
            {
                unlink(PUBLIC_PATH . $this->_dataOriginal['image']);
                unlink(PUBLIC_PATH . str_replace('team/','team/thumb/',$this->_dataOriginal['image']));
            }
            $this->image = '/assets/team/'.$newName;
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
            unlink(PUBLIC_PATH . str_replace('team/','team/thumb/',$this->image));
        }
        return parent::delete();
    }

    public function __get($name)
    {
        switch($name)
        {
            case 'imageThumb':
                return str_replace('team/','team/thumb/',$this->image);
                break;

            default:
                return parent::__get($name);
        }
    }
}