<?php

class PfoVerslagenController extends AppController
{
    public $name = 'PfoVerslagen';
    public $uses = array('PfoVerslag', 'PfoClient', 'PfoClientenVerslag');

    public function index()
    {
        $this->PfoVerslag->recursive = 0;
        $this->set('pfoVerslagen', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid pfo verslag', true));
            $this->redirect(array('action' => 'index'));
        }

        $pfoVerslag = $this->PfoVerslag->read(null, $id);
        $this->set('pfoVerslag', $pfoVerslag);
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->PfoVerslag->create();
            if ($this->PfoVerslag->save($this->data)) {
                $this->Session->setFlash(__('The pfo verslag has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The pfo verslag could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid pfo verslag', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->PfoVerslag->save($this->data)) {
                $this->Session->setFlash(__('The pfo verslag has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The pfo verslag could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->PfoVerslag->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for pfo verslag', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->PfoVerslag->delete($id)) {
            $this->Session->setFlash(__('Pfo verslag deleted', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash(__('Pfo verslag was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    public function verslag($id = null)
    {
        $medewerkers = $this->PfoVerslag->Medewerker->find('list');
        $pfo_client_id = null;
        if (!empty($this->data)) {
            $pfo_client_id = $this->data['PfoVerslag']['pfo_client_id'];
            $saved = false;
            //debug($this->data); die();
            if ($this->PfoVerslag->save($this->data)) {
                $id = $this->PfoVerslag->id;
                $pf=array();
                $pf[]=array(
                        'PfoClientenVerslag' => array(
                                'pfo_client_id' => $this->data['PfoClientenVerslag']['pfo_current_client_id'],
                                'pfo_verslag_id' => $id,
                        ),
                );

                if (isset($this->data['PfoClientenVerslag']['pfo_client_id'])) {
                    if (is_array($this->data['PfoClientenVerslag']['pfo_client_id'])) {
                        foreach ($this->data['PfoClientenVerslag']['pfo_client_id'] as $v) {
                            $pf[]=array(
                            'PfoClientenVerslag' => array(
                                'pfo_client_id' => $v,
                                'pfo_verslag_id' => $id,
                            ),
                        );
                        }
                    }
                }
                $conditions = array(
                    'pfo_verslag_id' => $id,
                );
                $this->PfoVerslag->PfoClientenVerslag->deleteAll($conditions);
                if ($this->PfoVerslag->PfoClientenVerslag->saveAll($pf)) {
                    $saved = true;
                }
            }
            if (! $saved) {
                $this->Session->setFlash(__('The pfo verslag could not be saved. Please, try again.', true));
                debug($this->PfoVerslag->validationErrors);
                //debug($this->PfoVerslag->PfoClientenVerslag->validationErrors);
                debug('die');
                die('die');
            }
        }

        $clienten = $this->PfoClient->clienten();
        $contact_type = $this->PfoVerslag->contact_type;
        $pfoClient = $this->PfoClient->read_complete($pfo_client_id);
        $this->set('pfo_client', $pfoClient);
        $this->setMedewerkers();
        $data=$this->PfoVerslag->read(null, $id);
        $this->set(compact('data', 'contact_type', 'medewerkers', 'pfoClient', 'clienten', 'pfo_client_id'));
        $this->render('/elements/pfo_verslag');
    }
}
