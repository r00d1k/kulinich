<?php
class ClinicController extends Core_Controller_Action_Abstract
{
    /**
     * Index Action.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->view->background = '/res/images/bg/clinic.jpg';
        $this->view->cases = System_Model_Mapper_Clinic_Case::getInstance()->with('cover')->findAll(
            array(
                 'cover.id' => array(
                     Core_Entity_Mapper_Abstract::FILTER_COND => '<>',
                     Core_Entity_Mapper_Abstract::FILTER_VALUE => null,
                 ),
                 'isEnabled' => 'yes'
            ),
            'rank ASC',
            6,
            $this->getRequest()->getParam('offset', 0)
        );
    }

    public function viewAction()
    {
        $clk = System_Model_Mapper_Clinic_Case::getInstance()->find(
            array(
                 'isEnabled' => 'yes',
                 'code' => $this->getRequest()->getParam('page-code')
            )
        );
        if($clk == null)
        {
            throw new Zend_Controller_Action_Exception('Clinic Case Not Found', 404);
        }
        $this->view->clinicCase = $clk;

        $this->view->photos = System_Model_Mapper_Clinic_Photo::getInstance()
            ->findAll(
                array(
                     'caseId' => $clk->getKey()
                ),
                'rank ASC'
            );
    }
}