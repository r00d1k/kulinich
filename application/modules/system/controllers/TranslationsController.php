<?php
class System_TranslationsController extends Core_Controller_Action_Scaffold
{
    /** @var System_Model_Mapper_Language string */
    protected $_mapper = 'System_Model_Mapper_Translation';
    protected $_captions = array(
        'index' => 'navigation:system-translations',
        'create' => 'navigation:system-translations-create',
        'edit'   => 'navigation:system-translations-edit'
    );

    public function createAction()
    {
        /** @var Core_Form $form  */
        $form = $this->_mapper->getForm();
        $trans = array();
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
        {
            $values = $form->getValues();
            foreach($values['translations'] as $lngId => $val)
            {
                if(!isset($trans[$lngId]))
                {
                    $trans[$lngId] = $this->_mapper->getModel();
                }
                $trans[$lngId]->value = $val['value'];
                $trans[$lngId]->section = $values['section'];
                $trans[$lngId]->key = $values['key'];
                $trans[$lngId]->languageId = $lngId;
                $trans[$lngId]->save();
            }
            $this->_clearCache();
            $this->_actionSuccess(
                $this->view->url(array('action' => 'index')),
                array(
                     'success' => true,
                     'reload'  => true,
                )
            );
        }

        $this->view->caption = !empty($this->_captions['create']) ? $this->_captions['create'] : 'create';
        $this->view->form = $form;
    }
    public function editAction()
    {
        $translations = $this->_mapper->findAll(
            array(
                 'section' => $this->getRequest()->getParam('section'),
                 'key' => $this->getRequest()->getParam('key'),
            )
        );
        if(empty($translations))
        {
            throw new Zend_Controller_Action_Exception('Page not found');
        }
        $populate = array();
        $trans = array();
        foreach($translations as $translation)
        {
            $populate['section'] = $translation->section;
            $populate['key'] = $translation->key;
            $populate['translations'][$translation->languageId]['value'] = $translation->value;
            $trans[$translation->languageId] = $translation;
        }

        /** @var Core_Form $form  */
        $form = $this->_mapper->getForm();
        $form->populate(
            array(Core_Form::MAIN_PART => $populate)
        );

        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
        {
            $values = $form->getValues();
            foreach($values['translations'] as $lngId => $val)
            {
                if(!isset($trans[$lngId]))
                {
                    $trans[$lngId] = $this->_mapper->getModel();
                }
                $trans[$lngId]->value = $val['value'];
                $trans[$lngId]->section = $values['section'];
                $trans[$lngId]->key = $values['key'];
                $trans[$lngId]->languageId = $lngId;
                $trans[$lngId]->save();
            }
            $this->_clearCache();
            $this->_actionSuccess(
                $this->view->url(array('action' => 'index')),
                array(
                     'success' => true,
                     'reload'  => true,
                )
            );
        }

        $this->view->caption = !empty($this->_captions['edit']) ? $this->_captions['edit'] : 'edit';
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $translations = $this->_mapper->findAll(
            array(
                 'section' => $this->getRequest()->getParam('section'),
                 'key' => $this->getRequest()->getParam('key'),
            )
        );
        $translations->delete();
        $this->_clearCache();
        $this->redirect($this->view->url(array('action' => 'index')));
        exit();
    }
    protected function _clearCache()
    {
        /** @var Core_Cache_Manager $manager */
        $manager = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cacheManager');
        /** @var Zend_Cache_Core $cache */
        $cache = $manager->getCache('translate');
        $cache->clean();
    }
}