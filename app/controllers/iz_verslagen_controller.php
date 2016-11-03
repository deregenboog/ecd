<?php

class IzVerslagenController extends AppController
{
    public $name = 'IzVerslagen';

    public function add_edit($id)
    {
        $iz_deelnemer['IzDeelnemer'] = $this->IzVerslag->IzDeelnemer->getById($id);
        $iz_koppeling_id = $this->getParam('iz_koppeling_id');

        if (empty($iz_deelnemer)) {
            $this->Session->setFlash(__('ID bestaat niet', true));
            $this->redirect('/');
        }

        $persoon_model = $iz_deelnemer['IzDeelnemer']['model'];
        $foreign_key = $iz_deelnemer['IzDeelnemer']['foreign_key'];

        if (!empty($this->data)) {
            $verslag = array();

            if (!empty($this->data['IzVerslag']['id'])) {
                $iz_k_id = $this->data['IzVerslag']['iz_koppeling_id'];
                $verslag = $this->IzVerslag->getById($this->data['IzVerslag']['id']);

                if ($verslag['iz_koppeling_id'] != $iz_k_id) {
                    $this->Session->setFlash(__('ID bestaat niet', true));
                    $this->redirect('/');
                }
            }

            if (empty($this->data['IzVerslag']['id'])) {
                $this->IzVerslag->create();
                $this->data['IzVerslag']['iz_deelnemer_id'] = $id;
                $this->data['IzVerslag']['medewerker_id'] = $this->Session->read('Auth.Medewerker.id');
            }

            $this->data['IzVerslag']['opmerking'] = $this->data['IzVerslag']['opmerking'];

            if ($this->IzVerslag->save($this->data)) {
                $this->Session->setFlash(__('Het iz verslag is opgeslagen', true));
            } else {
                $this->Session->setFlash(__('Het iz verslag kan niet worden opgeslagen', true));
            }
        }

        if (!empty($iz_koppeling_id)) {
            $this->redirect(array('controller' => 'iz_deelnemers', 'action' => 'verslagen', $id, 'iz_koppeling_id' => $iz_koppeling_id));
        } else {
            $this->redirect(array('controller' => 'iz_deelnemers', 'action' => 'verslagen_persoon', $id));
        }
    }
}
