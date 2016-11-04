<?php

class LandenController extends AppController
{
    public $name = 'Landen';

    public function index()
    {
        $this->Land->recursive = 0;
        $this->set('landen', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid land', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('land', $this->Land->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Land->create();
            if ($this->Land->save($this->data)) {
                $this->flashError(__('The land has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The land could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid land', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Land->save($this->data)) {
                $this->flashError(__('The land has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The land could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Land->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for land', true));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->Land->delete($id)) {
            $this->flashError(__('Land deleted', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->flashError(__('Land was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
