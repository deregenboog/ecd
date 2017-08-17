<?php

class VerslavingsperiodesController extends AppController
{
    public $name = 'Verslavingsperiodes';

    public function index()
    {
        $this->Verslavingsperiode->recursive = 0;
        $this->set('verslavingsperiodes', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid verslavingsperiode', true));
            $this->redirect(['action' => 'index']);
        }
        $this->set('verslavingsperiode', $this->Verslavingsperiode->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Verslavingsperiode->create();
            if ($this->Verslavingsperiode->save($this->data)) {
                $this->flashError(__('The verslavingsperiode has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The verslavingsperiode could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid verslavingsperiode', true));
            $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->Verslavingsperiode->save($this->data)) {
                $this->flashError(__('The verslavingsperiode has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The verslavingsperiode could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Verslavingsperiode->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for verslavingsperiode', true));
            $this->redirect(['action' => 'index']);
        }
        if ($this->Verslavingsperiode->delete($id)) {
            $this->flashError(__('Verslavingsperiode deleted', true));
            $this->redirect(['action' => 'index']);
        }
        $this->flashError(__('Verslavingsperiode was not deleted', true));
        $this->redirect(['action' => 'index']);
    }
}
