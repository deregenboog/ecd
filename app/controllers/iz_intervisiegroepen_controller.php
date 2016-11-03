<?php

class IzIntervisiegroepenController extends AppController
{
    public $name = 'IzIntervisiegroepen';

    public function index()
    {
        $this->IzIntervisiegroep->recursive = 0;
        $this->set('izIntervisiegroepen', $this->paginate());
        $this->setMedewerkers();
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid iz intervisiegroep', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('izIntervisiegroep', $this->IzIntervisiegroep->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->IzIntervisiegroep->create();

            if ($this->IzIntervisiegroep->save($this->data)) {
                $this->Session->setFlash(__('The iz intervisiegroep has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The iz intervisiegroep could not be saved. Please, try again.', true));
            }
        }
        $this->setMedewerkers();
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid iz intervisiegroep', true));
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->data)) {
            if ($this->IzIntervisiegroep->save($this->data)) {
                $this->Session->setFlash(__('The iz intervisiegroep has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The iz intervisiegroep could not be saved. Please, try again.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->IzIntervisiegroep->read(null, $id);
        }

        $this->setMedewerkers();
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for iz intervisiegroep', true));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->IzIntervisiegroep->delete($id)) {
            $this->Session->setFlash(__('Iz intervisiegroep deleted', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->Session->setFlash(__('Iz intervisiegroep was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
