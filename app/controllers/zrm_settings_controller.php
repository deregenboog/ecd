<?php

class ZrmSettingsController extends AppController
{
    public $name = 'ZrmSettings';

    public function index()
    {
        $this->ZrmSetting->recursive = 0;
        $this->set('zrmSettings', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid zrm setting', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('zrmSetting', $this->ZrmSetting->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->ZrmSetting->create();
            if ($this->ZrmSetting->save($this->data)) {
                $this->ZrmSetting->clear_cache();
                $this->Session->setFlash(__('The zrm setting has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The zrm setting could not be saved. Please, try again.', true));
            }
        }
    }

    public function matrix()
    {
        $this->loadModel('ZrmReport');
        if (!empty($this->data)) {
            $this->ZrmSetting->create();

            if ($this->ZrmSetting->saveAll($this->data['ZrmSetting'])) {
                $this->ZrmSetting->clear_cache();
                $this->Session->setFlash(__('ZRM settings zijn opgeslagen', true));
            } else {
                $this->Session->setFlash(__('ZRM settings niet opgeslagen.', true));
            }
        }
        
        $zrm_settings = $this->ZrmSetting->find('all');
        $this->set('zrm_settings', $zrm_settings);
        $this->set('zrm_data', $this->ZrmReport->zrm_data());
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid zrm setting', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->ZrmSetting->save($this->data)) {
                $this->ZrmSetting->clear_cache();
                $this->Session->setFlash(__('The zrm setting has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The zrm setting could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->ZrmSetting->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for zrm setting', true));
            $this->redirect(array('action'=>'index'));
        }
        
        if ($this->ZrmSetting->delete($id)) {
            $this->Session->setFlash(__('Zrm setting deleted', true));
            $this->redirect(array('action'=>'index'));
        }
        
        $this->Session->setFlash(__('Zrm setting was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    public function update_table()
    {
        $this->ZrmSetting->update_table();
        $this->redirect(array('controller' => 'admin'));
    }
}
