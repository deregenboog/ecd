<?php

class LocatiesController extends AppController
{
    public $name = 'Locaties';

    public function index()
    {
        $this->Locatie->recursive = 0;
        $this->set('locaties', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid locatie', true));
            $this->redirect(['action' => 'index']);
        }
        $this->set('locatie', $this->Locatie->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Locatie->create();
            if ($this->Locatie->save($this->data)) {
                $this->flashError(__('The locatie has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The locatie could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid locatie', true));
            $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->Locatie->save($this->data)) {
                $this->flashError(__('The locatie has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The locatie could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Locatie->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for locatie', true));
            $this->redirect(['action' => 'index']);
        }
        if ($this->Locatie->delete($id)) {
            $this->flashError(__('Locatie deleted', true));
            $this->redirect(['action' => 'index']);
        }
        $this->flashError(__('Locatie was not deleted', true));
        $this->redirect(['action' => 'index']);
    }
}
