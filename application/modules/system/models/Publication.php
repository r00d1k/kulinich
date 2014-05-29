<?php
class System_Model_Publication extends Core_Entity_Model_Abstract
{
    public function save()
    {
        $assetsLocation = PUBLIC_PATH.'/assets/publication/';
        if($this->image != null && stristr($this->image, 'tmp') && is_file(PUBLIC_PATH . $this->image))
        {
            $newName = uniqid('image_').'.png';
            Core_Image::factory(PUBLIC_PATH . $this->image)
                ->resize(343,243, Core_Image::RESIZE_CROP)
                ->save($assetsLocation.$newName);
            unlink(PUBLIC_PATH . $this->image);
            $this->image = '/assets/publication/'.$newName;
            if(!empty($this->_dataOriginal['image']) && is_file($this->_dataOriginal['image']))
            {
                unlink(PUBLIC_PATH . $this->_dataOriginal['image']);
            }
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
        }
        return parent::delete();
    }
}