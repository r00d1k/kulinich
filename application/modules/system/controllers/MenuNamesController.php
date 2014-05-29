<?php
    class System_MenuNamesController extends Core_Controller_Action_Scaffold
    {
        /** @var System_Model_Mapper_Language string */
        protected $_mapper = 'System_Model_Mapper_Translation';
        protected $_captions = array(
            'index' => 'navigation:menu-names',
            'create' => 'navigation:menu-names-create',
            'edit'   => 'navigation:menu-names-edit'
        );

        public function indexAction()
        {
            $base = $this->_mapper->getStorage()->getBaseQuery();
            $base->group(
                array(
                     $this->_mapper->map('section'),
                     $this->_mapper->map('key'),
                )
            );
            $base->reset(Zend_Db_Select::COLUMNS);
            $fields = $this->_mapper->getFields();
            $fields['languages'] = new Zend_Db_Expr('GROUP_CONCAT('.$fields['languageId'].' SEPARATOR \' \')');
            $base->columns($fields);
            $config = $this->_mapper->getConfig();
            $base->where('translation_module = ?', 'navigation-contents');
            $this->view->grid = new Core_Grid_Html($base, $config['gridMenu']);

            $this->view->caption = !empty($this->_captions['index']) ? $this->_captions['index'] : 'list';
            $this->view->addAction = !empty($this->_captions['create']) ? $this->_captions['create'] : null;
            echo $this->view->render('index.phtml');
            $this->_disableRender();
        }

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

        protected function _clearCache()
        {
            /** @var Core_Cache_Manager $manager */
            $manager = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cacheManager');
            /** @var Zend_Cache_Core $cache */
            $cache = $manager->getCache('translate');
            $cache->clean();
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
            $this->redirect($this->view->url(array('action' => 'index')));
            exit();
        }
    }