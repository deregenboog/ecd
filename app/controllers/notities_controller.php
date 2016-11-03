<?php

class NotitiesController extends AppController
{
    public $name = 'Notities';

    public function index()
    {
        $this->Notitie->recursive = 0;
        $this->set('notities', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid notitie', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('notitie', $this->Notitie->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Notitie->create();
            if ($this->Notitie->save($this->data)) {
                $this->flash(__('The notitie has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The notitie could not be saved. Please, try again.', true));
            }
        }
        $klanten = $this->Notitie->Klant->find('list');
        $medewerkers = $this->Notitie->Medewerker->find('list');
        $this->set(compact('klanten', 'medewerkers'));
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid notitie', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Notitie->save($this->data)) {
                $this->flash(__('The notitie has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The notitie could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Notitie->read(null, $id);
        }
        $klanten = $this->Notitie->Klant->find('list');
        $medewerkers = $this->Notitie->Medewerker->find('list');
        $this->set(compact('klanten', 'medewerkers'));
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for notitie', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Notitie->delete($id)) {
            $this->flashError(__('Notitie deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->flashError(__('Notitie was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
