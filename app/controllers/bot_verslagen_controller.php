<?php

class BotVerslagenController extends AppController
{
	public $name = 'BotVerslagen';

	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->back_on_track_coach = false;
		if (array_key_exists(GROUP_BACK_ON_TRACK_COACH, $this->userGroups)) {
			$this->back_on_track_coach = true;
		}
		$this->set('back_on_track_coach', $this->back_on_track_coach);
	}

	public function edit($id = null)
	{
		$bot_client_id = null;

		if (!empty($this->data)) {
			
			$botKlantId = $this->data['BotVerslag']['klant_id'];
			$saved = false;

			if (! $this->BotVerslag->save($this->data)) {
				
				$this->flashError(__('The bot verslag could not be saved. Please, try again.', true));
				$this->redirect("/");
			}
		}

		$contactTypes = $this->BotVerslag->contact_type;
		
		$data = $this->BotVerslag->read(null, $id);
		$data['BotVerslag']['Medewerker']['name'] = $this->Session->read('Auth.Medewerker.LdapUser.displayname');
		
		$this->set(compact('data', 'contactTypes'));
		
		$this->render('/elements/bot_verslag');
	}

	private function checkPermissions($forKlant)
	{
		if (! $this->back_on_track_coach) {
			return true;
		}

		$today = strtotime('today');
		$valid = true;

		if (empty($forKlant['BackOnTrack']['startdatum']) ||
			empty($forKlant['BackOnTrack']['einddatum'])) {
			$valid = false;
		}

		if (strtotime($forKlant['BackOnTrack']['startdatum']) > $today
			|| strtotime($forKlant['BackOnTrack']['einddatum']) < $today) {
			$valid = false;
		}

		if (!$valid) {
			$this->flashError(__('Invalid back on track', true));
			$this->redirect(array('action' => 'index'));
		}
	}
}
