<?php

class GroepsactiviteitenIntakesController extends AppController
{
    public $name = 'GroepsactiviteitenIntakes';

    public function add_edit($persoon_model, $foreign_key)
    {
        if (!empty($this->data)) {
            if (empty($this->data['GroepsactiviteitenIntake']['id'])) {
                $this->GroepsactiviteitenIntake->create();
                $this->data['GroepsactiviteitenIntake']['model'] = $persoon_model;
                $this->data['GroepsactiviteitenIntake']['foreign_key'] = $foreign_key;
                $this->data['GroepsactiviteitenIntake']['medewerker_id'] = $this->Session->read('Auth.Medewerker.id');
            }

            $this->data['GroepsactiviteitenIntake']['gespreksverslag'] = nl2br(htmlentities($this->data['GroepsactiviteitenIntake']['gespreksverslag']));
            if ($this->GroepsactiviteitenIntake->save($this->data)) {
                $this->data['ZrmReport']['model'] = 'Intake';
                $this->data['ZrmReport']['foreign_key'] = $this->Intake->id;
                $this->data['ZrmReport']['klant_id'] = $klant_id;

                $this->ZrmReport->create();

                if ($this->ZrmReport->save($this->data)) {
                    $this->Intake->commit();
                    $this->flash(__('De intake is opgeslagen', true));
                    $this->redirect(array('controller' => 'klanten', 'action' => 'view', $klant_id));
                }

                $this->Session->setFlash(__('Success', true));
            } else {
                $this->Session->setFlash(__('Error', true));
            }
        }

        $this->redirect(array('controller' => 'Groepsactiviteiten', 'action' => 'intakes', $persoon_model, $foreign_key));
    }
}
