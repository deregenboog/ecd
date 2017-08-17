<?php

class IzOntstaanContactenController extends AppController
{
    public $name = 'IzOntstaanContacten';

    public function index()
    {
        $this->IzOntstaanContact->recursive = 0;
        $this->set('izOntstaanContacten', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid iz ontstaan contact', true));
            $this->redirect(['action' => 'index']);
        }
        $this->set('izOntstaanContact', $this->IzOntstaanContact->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->IzOntstaanContact->create();

            if ($this->IzOntstaanContact->save($this->data)) {
                $this->Session->setFlash(__('The iz ontstaan contact has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash(__('The iz ontstaan contact could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid iz ontstaan contact', true));
            $this->redirect(['action' => 'index']);
        }

        if (!empty($this->data)) {
            if ($this->IzOntstaanContact->save($this->data)) {
                $this->Session->setFlash(__('The iz ontstaan contact has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash(__('The iz ontstaan contact could not be saved. Please, try again.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->IzOntstaanContact->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for iz ontstaan contact', true));
            $this->redirect(['action' => 'index']);
        }

        if ($this->IzOntstaanContact->delete($id)) {
            $this->Session->setFlash(__('Iz ontstaan contact deleted', true));
            $this->redirect(['action' => 'index']);
        }

        $this->Session->setFlash(__('Iz ontstaan contact was not deleted', true));
        $this->redirect(['action' => 'index']);
    }
}
