<?php

class GeslachtenController extends AppController
{
    public $name = 'Geslachten';

    public function index()
    {
        $this->Geslacht->recursive = 0;
        $this->set('geslachten', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid geslacht', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('geslacht', $this->Geslacht->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Geslacht->create();
            if ($this->Geslacht->save($this->data)) {
                $this->flash(__('The geslacht has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The geslacht could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid geslacht', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Geslacht->save($this->data)) {
                $this->flash(__('The geslacht has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The geslacht could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Geslacht->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for geslacht', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Geslacht->delete($id)) {
            $this->flashError(__('Geslacht deleted', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->flashError(__('Geslacht was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
