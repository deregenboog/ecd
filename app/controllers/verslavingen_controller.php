<?php

class VerslavingenController extends AppController
{
    public $name = 'Verslavingen';

    public function index()
    {
        $this->Verslaving->recursive = 0;
        $this->set('verslavingen', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid verslaving', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('verslaving', $this->Verslaving->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Verslaving->create();
            if ($this->Verslaving->save($this->data)) {
                $this->flash(__('The verslaving has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The verslaving could not be saved. Please, try again.', true));
            }
        }
        $intakes = $this->Verslaving->Intake->find('list');
        $this->set(compact('intakes'));
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid verslaving', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Verslaving->save($this->data)) {
                $this->flash(__('The verslaving has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The verslaving could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Verslaving->read(null, $id);
        }
        $intakes = $this->Verslaving->Intake->find('list');
        $this->set(compact('intakes'));
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for verslaving', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Verslaving->delete($id)) {
            $this->flashError(__('Verslaving deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->flashError(__('Verslaving was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
