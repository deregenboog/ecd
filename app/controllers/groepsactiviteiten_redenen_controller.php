<?php

class GroepsactiviteitenRedenenController extends AppController
{
    public $name = 'GroepsactiviteitenRedenen';

    public function index()
    {
        $this->GroepsactiviteitenReden->recursive = 0;
        $this->set('groepsactiviteitenRedenen', $this->paginate());
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->GroepsactiviteitenReden->create();
            if ($this->GroepsactiviteitenReden->save($this->data)) {
                $this->Session->setFlash(__('De reden is opgeslagen', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Reden kan niet worden opgeslagen', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Niet geldige reden', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->data['GroepsactiviteitenReden']['id'] = $id;
            if ($this->GroepsactiviteitenReden->save($this->data)) {
                $this->Session->setFlash(__('De reden is opgeslagen', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Reden kan niet worden opgeslagen', true));
            }
        }
        if (empty($this->data)) {
            $this->GroepsactiviteitenReden->recursive = 0;
            $this->data = $this->GroepsactiviteitenReden->read(null, $id);
        }
    }
}
