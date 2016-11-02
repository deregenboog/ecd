<?php

class IzProjectenController extends AppController
{
    public $name = 'IzProjecten';

    public function index()
    {
        $this->IzProject->recursive = 0;
        $this->set('izProjecten', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid iz project', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('izProject', $this->IzProject->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->IzProject->create();
            if ($this->IzProject->save($this->data)) {
                $this->Session->setFlash(__('The iz project has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The iz project could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid iz project', true));
            $this->redirect(array('action' => 'index'));
        }
        
        if (!empty($this->data)) {
            if ($this->IzProject->save($this->data)) {
                $this->Session->setFlash(__('The iz project has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The iz project could not be saved. Please, try again.', true));
            }
        }
        
        if (empty($this->data)) {
            $this->data = $this->IzProject->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for iz project', true));
            $this->redirect(array('action'=>'index'));
        }
        
        if ($this->IzProject->delete($id)) {
            $this->Session->setFlash(__('Iz project deleted', true));
            $this->redirect(array('action'=>'index'));
        }
        
        $this->Session->setFlash(__('Iz project was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
