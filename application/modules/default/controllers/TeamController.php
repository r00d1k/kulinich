<?php
class TeamController extends Core_Controller_Action_Abstract
{
    /**
     * Index Action.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_setLayout('default');
        $this->view->background = '/res/images/bg/team.jpg';
        $photos = System_Model_Mapper_Team::getInstance()->findAll(array( ),'rank ASC');

        $parts = array();
        $part = array();
        $index = 0;
        foreach($photos as $photo)
        {
            if($photo->useBigPhoto == 'yes')
            {
                if(!empty($part))
                {
                    while(count($part) < 4)
                    {
                        $part[] = null;
                    }
                    $parts[] = $part;
                }
                $part = array();
                $index = 0;
                $parts[] = $photo;
            }
            else
            {
                $part[] = $photo;
                $index++;
                if($index > 2)
                {
                    $part[] = null;
                    $parts[] = $part;
                    $part = array();
                    $index = 0;
                }
            }

        }
        if(!empty($part))
        {
            $parts[] = $part;
        }

        //Zend_Debug::dump($parts);exit();
        $this->view->photos = $parts;
    }
}