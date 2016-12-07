<?php

class PfoAardRelatiesController extends AppController
{
    public $name = 'PfoAardRelaties';

    public function index()
    {
        $this->PfoAardRelatie->recursive = 0;
        $this->set('pfoAardRelaties', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid pfo aard relatie', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('pfoAardRelatie', $this->PfoAardRelatie->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->PfoAardRelatie->create();
            if ($this->PfoAardRelatie->save($this->data)) {
                $this->Session->setFlash(__('The pfo aard relatie has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The pfo aard relatie could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid pfo aard relatie', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->PfoAardRelatie->save($this->data)) {
                $this->Session->setFlash(__('The pfo aard relatie has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The pfo aard relatie could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->PfoAardRelatie->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for pfo aard relatie', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->PfoAardRelatie->delete($id)) {
            $this->Session->setFlash(__('Pfo aard relatie deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Pfo aard relatie was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
