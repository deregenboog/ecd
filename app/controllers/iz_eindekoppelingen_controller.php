<?php

class IzEindekoppelingenController extends AppController
{
    public $name = 'IzEindekoppelingen';

    public function index()
    {
        $this->IzEindekoppeling->recursive = 0;
        $this->set('izEindekoppelingen', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid iz eindekoppeling', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('izEindekoppeling', $this->IzEindekoppeling->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->IzEindekoppeling->create();
            if ($this->IzEindekoppeling->save($this->data)) {
                $this->Session->setFlash(__('The iz eindekoppeling has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The iz eindekoppeling could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid iz eindekoppeling', true));
            $this->redirect(array('action' => 'index'));
        }
        
        if (!empty($this->data)) {
            if ($this->IzEindekoppeling->save($this->data)) {
                $this->Session->setFlash(__('The iz eindekoppeling has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The iz eindekoppeling could not be saved. Please, try again.', true));
            }
        }
        
        if (empty($this->data)) {
            $this->data = $this->IzEindekoppeling->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for iz eindekoppeling', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->IzEindekoppeling->delete($id)) {
            $this->Session->setFlash(__('Iz eindekoppeling deleted', true));
            $this->redirect(array('action'=>'index'));
        }
        
        $this->Session->setFlash(__('Iz eindekoppeling was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
