<?php

class InkomensController extends AppController
{
    public $name = 'Inkomens';

    public function index()
    {
        $this->Inkomen->recursive = 0;
        $this->set('inkomens', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid inkomen', true));
            $this->redirect(['action' => 'index']);
        }
        $this->set('inkomen', $this->Inkomen->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Inkomen->create();
            if ($this->Inkomen->save($this->data)) {
                $this->flashError(__('The inkomen has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The inkomen could not be saved. Please, try again.', true));
            }
        }
        $intakes = $this->Inkomen->Intake->find('list');
        $this->set(compact('intakes'));
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid inkomen', true));
            $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->Inkomen->save($this->data)) {
                $this->flashError(__('The inkomen has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The inkomen could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Inkomen->read(null, $id);
        }
        $intakes = $this->Inkomen->Intake->find('list');
        $this->set(compact('intakes'));
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for inkomen', true));
            $this->redirect(['action' => 'index']);
        }
        if ($this->Inkomen->delete($id)) {
            $this->flashError(__('Inkomen deleted', true));
            $this->redirect(['action' => 'index']);
        }
        $this->flashError(__('Inkomen was not deleted', true));
        $this->redirect(['action' => 'index']);
    }
}
