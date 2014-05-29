<?php
class Core_Grid_Modifier_Cat extends Core_Grid_Modifier_Abstract
{
    public function _process($value)
    {
        $appendString = '';
        if(in_array('dots', $this->_options))
        {
            $appendString .= ' ...';
        }
        $length = $this->_options[0];
        if(strlen($value) < $length)
        {
            return $value;
        }
        $cuttedPart = substr($value, 0, $length);
        if(!in_array('word', $this->_options))
        {
            return $cuttedPart.$appendString;
        }

        $value = substr($value, $length);

        $dotPoint = strpos('.', $value);
        $commaPoint = strpos('.', $value);
        if($commaPoint < $dotPoint)
        {
            $splitPoint = $commaPoint;
        }
        else
        {
            $splitPoint = $dotPoint;
        }
        $spacePoint = strpos(' ', $value);

        if($spacePoint < $splitPoint)
        {
            $splitPoint = $spacePoint;
        }
        if($splitPoint <= (($length/4)))
        {
            $cuttedPart .= substr($value, 0, $splitPoint);
        }
        return $cuttedPart.$appendString;
    }
}