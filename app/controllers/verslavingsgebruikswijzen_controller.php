<?php

class VerslavingsgebruikswijzenController extends AppController
{
    public $name = 'Verslavingsgebruikswijzen';

    public function index()
    {
        $this->Verslavingsgebruikswijze->recursive = 0;
        $this->set('verslavingsgebruikswijzen', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid verslavingsgebruikswijze', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('verslavingsgebruikswijze', $this->Verslavingsgebruikswijze->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Verslavingsgebruikswijze->create();
            if ($this->Verslavingsgebruikswijze->save($this->data)) {
                $this->flash(__('The verslavingsgebruikswijze has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The verslavingsgebruikswijze could not be saved. Please, try again.', true));
            }
        }
        $intakes = $this->Verslavingsgebruikswijze->Intake->find('list');
        $this->set(compact('intakes'));
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid verslavingsgebruikswijze', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Verslavingsgebruikswijze->save($this->data)) {
                $this->flash(__('The verslavingsgebruikswijze has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The verslavingsgebruikswijze could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Verslavingsgebruikswijze->read(null, $id);
        }
        $intakes = $this->Verslavingsgebruikswijze->Intake->find('list');
        $this->set(compact('intakes'));
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for verslavingsgebruikswijze', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Verslavingsgebruikswijze->delete($id)) {
            $this->flashError(__('Verslavingsgebruikswijze deleted', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->flashError(__('Verslavingsgebruikswijze was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
