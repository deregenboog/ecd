<?php

class GroepsactiviteitenVerslagenController extends AppController
{
	public $name = 'GroepsactiviteitenVerslagen';

	public function add_edit($persoon_model = 'Klant', $foreign_key)
	{
		if (!empty($this->data)) {
			if (empty($this->data['GroepsactiviteitenVerslag']['id'])) {
				$this->GroepsactiviteitenVerslag->create();
				$this->data['GroepsactiviteitenVerslag']['model'] = $persoon_model;
				$this->data['GroepsactiviteitenVerslag']['foreign_key'] = $foreign_key;
				$this->data['GroepsactiviteitenVerslag']['medewerker_id'] = $this->Session->read('Auth.Medewerker.id');
			}

			#$this->data['GroepsactiviteitenVerslag']['opmerking'] = nl2br(htmlentities($this->data['GroepsactiviteitenVerslag']['opmerking']));
			if ($this->GroepsactiviteitenVerslag->save($this->data)) {
				$this->Session->setFlash(__('The groepsactiviteiten verslag has been saved', true));
			} else {
				$this->Session->setFlash(__('The groepsactiviteiten verslag could not be saved. Please, try again.', true));
			}
		}

		$this->redirect(array('controller' => 'Groepsactiviteiten', 'action' => 'verslagen', $persoon_model, $foreign_key));
	}
}
