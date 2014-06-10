<?php
class System_Model_Clinic_Photo extends Core_Entity_Model_Abstract
{
    public function save()
    {
        if($this->_isChanged)
        {
            $assetsLocation = PUBLIC_PATH.'/assets/clinic/';
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
                    ->resize(392,261, Core_Image::RESIZE_CROP)
                    ->save($assetsLocation.$newName);

                Core_Image::factory(PUBLIC_PATH . $this->image)
                    ->resize(161,101, Core_Image::RESIZE_CROP)
                    ->save($assetsLocation . 'thumb/' . $newName);

                Core_Image::factory(PUBLIC_PATH . $this->image)
                    ->resize(1366,768, Core_Image::RESIZE_FIT_SMALLER)
                    ->save($assetsLocation . 'large/' . $newName);

                unlink(PUBLIC_PATH . $this->image);

                if(!empty($this->_dataOriginal['image']) && is_file(PUBLIC_PATH . $this->_dataOriginal['image']))
                {
                    @unlink(PUBLIC_PATH . $this->_dataOriginal['image']);
                    @unlink(PUBLIC_PATH . str_replace('clinic/','clinic/thumb/',$this->_dataOriginal['image']));
                    @unlink(PUBLIC_PATH . str_replace('clinic/','clinic/large/',$this->_dataOriginal['image']));
                }
                $this->image = '/assets/clinic/'.$newName;
            }
            elseif($this->image != null && !file_exists(PUBLIC_PATH . $this->image))
            {
                $this->image = null;
            }

            if($this->isCover == 'yes')
            {
                Zend_Db_Table::getDefaultAdapter()->query('
                    UPDATE `'.$this->getMapper()->getStorage()->info('name').'`
                    SET `' . $this->getMapper()->map('isCover') . '` = \'no\'
                    WHERE `' . $this->getMapper()->map('caseId') . '` = '.$this->caseId.'
                ')->execute();
            }
        }
        return parent::save();
    }

    public function delete()
    {
        if($this->image != null && is_file(PUBLIC_PATH . $this->image))
        {
            @unlink(PUBLIC_PATH . $this->image);
            @unlink(PUBLIC_PATH . str_replace('clinic/','clinic/thumb/',$this->image));
            @unlink(PUBLIC_PATH . str_replace('clinic/','clinic/large/',$this->image));
        }
        return parent::delete();
    }

    public function __get($name)
    {
        switch($name)
        {
            case 'imageThumb':
                return str_replace('clinic/','clinic/thumb/',$this->image);
                break;
            case 'imageLarge':
                return str_replace('clinic/','clinic/large/',$this->image);
                break;
            default:
                return parent::__get($name);
        }
    }
}