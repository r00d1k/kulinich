<?php
class System_Model_Static_Page extends Core_Entity_Model_Abstract
{
    public function save()
    {
        $assetsLocation = PUBLIC_PATH.'/assets/static/';
        if(!is_dir($assetsLocation))
        {
            mkdir($assetsLocation,0777,true);
            chmod($assetsLocation, 0777);
        }
        if($this->background != null && stristr($this->background, 'tmp') && is_file(PUBLIC_PATH . $this->background))
        {
            $newName = uniqid('image_').'.jpg';
            $image = Core_Image::factory(PUBLIC_PATH . $this->background)->save($assetsLocation.$newName);

            unlink(PUBLIC_PATH . $this->background);

            if(!empty($this->_dataOriginal['background']) && is_file(PUBLIC_PATH . $this->_dataOriginal['background']))
            {
                unlink(PUBLIC_PATH . $this->_dataOriginal['background']);
            }
            $this->background = '/assets/static/'.$newName;
        }
        elseif($this->background != null && !file_exists(PUBLIC_PATH . $this->background))
        {
            $this->background = null;
        }
        return parent::save();
    }

    public function delete()
    {
        if($this->background != null && is_file(PUBLIC_PATH . $this->background))
        {
            unlink(PUBLIC_PATH . $this->background);
        }
        return parent::delete();
    }
}