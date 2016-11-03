<?php

class IzViaPersonenController extends AppController
{
    public $name = 'IzViaPersonen';

    public function index()
    {
        $this->IzViaPersoon->recursive = 0;
        $this->set('izViaPersonen', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid iz via persoon', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('izViaPersoon', $this->IzViaPersoon->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->IzViaPersoon->create();
            if ($this->IzViaPersoon->save($this->data)) {
                $this->Session->setFlash(__('The iz via persoon has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The iz via persoon could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid iz via persoon', true));
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->data)) {
            if ($this->IzViaPersoon->save($this->data)) {
                $this->Session->setFlash(__('The iz via persoon has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The iz via persoon could not be saved. Please, try again.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->IzViaPersoon->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for iz via persoon', true));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->IzViaPersoon->delete($id)) {
            $this->Session->setFlash(__('Iz via persoon deleted', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->Session->setFlash(__('Iz via persoon was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
