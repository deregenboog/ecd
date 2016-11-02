<?php

class PfoClientenVerslagenController extends AppController
{
    public $name = 'PfoClientenVerslagen';

    public function index()
    {
        $this->PfoClientenVerslag->recursive = 0;
        $this->set('pfoClientenVerslagen', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid pfo clienten verslag', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('pfoClientenVerslag', $this->PfoClientenVerslag->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->PfoClientenVerslag->create();
            if ($this->PfoClientenVerslag->save($this->data)) {
                $this->Session->setFlash(__('The pfo clienten verslag has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The pfo clienten verslag could not be saved. Please, try again.', true));
            }
        }
        $pfoClienten = $this->PfoClientenVerslag->PfoClient->find('list');
        $pfoVerslagen = $this->PfoClientenVerslag->PfoVerslag->find('list');
        $this->set(compact('pfoClienten', 'pfoVerslagen'));
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid pfo clienten verslag', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->PfoClientenVerslag->save($this->data)) {
                $this->Session->setFlash(__('The pfo clienten verslag has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The pfo clienten verslag could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->PfoClientenVerslag->read(null, $id);
        }
        $pfoClienten = $this->PfoClientenVerslag->PfoClient->find('list');
        $pfoVerslagen = $this->PfoClientenVerslag->PfoVerslag->find('list');
        $this->set(compact('pfoClienten', 'pfoVerslagen'));
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for pfo clienten verslag', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->PfoClientenVerslag->delete($id)) {
            $this->Session->setFlash(__('Pfo clienten verslag deleted', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash(__('Pfo clienten verslag was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
