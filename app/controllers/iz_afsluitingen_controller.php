<?php

class IzAfsluitingenController extends AppController
{
    public $name = 'IzAfsluitingen';

    public function index()
    {
        $this->IzAfsluiting->recursive = 0;
        $this->set('izAfsluitingen', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid iz afsluiting', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('izAfsluiting', $this->IzAfsluiting->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->IzAfsluiting->create();
            if ($this->IzAfsluiting->save($this->data)) {
                $this->Session->setFlash(__('The iz afsluiting has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The iz afsluiting could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid iz afsluiting', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->IzAfsluiting->save($this->data)) {
                $this->Session->setFlash(__('The iz afsluiting has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The iz afsluiting could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->IzAfsluiting->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for iz afsluiting', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->IzAfsluiting->delete($id)) {
            $this->Session->setFlash(__('Iz afsluiting deleted', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash(__('Iz afsluiting was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
