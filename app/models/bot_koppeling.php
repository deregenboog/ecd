<?php

class BotKoppeling extends AppModel
{
	public $name = 'BotKoppeling';

	public $actsAs = array(
			'Containable',
	);

	public $belongsTo = array(
		'Medewerker' => array(
			'className' => 'Medewerker',
			'foreignKey' => 'medewerker_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
		'BackOnTrack' => array(
			'className' => 'BackOnTrack',
			'foreignKey' => 'back_on_track_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
	);

	public $validate = array(
		'startdatum' => array(
			'notempty' => array(
				'rule' => array(
					'notEmpty',
				),
				'message' => 'Voer een startdatum in',
				//'allowEmpty' => false,
				'required' => true,
			),
		),
		'medewerker_id' => array(
			'notempty' => array(
				'rule' => array(
					'notEmpty',
				),
				'message' => 'Voer een coach in',
				//'allowEmpty' => false,
				'required' => true,
			),
		),
		'einddatum' => array(
			'rule'	  => array('validate_einddatum'),
			'message' => 'Datum intake groter dan datum aanmelding',
		),
	);

	public function validate_einddatum($check)
	{
		if (empty($this->data['BotKoppeling']['startdatum'])) {
			return true;
		}
		
		if (empty($this->data['BotKoppeling']['einddatum'])) {
			return true;
		}
		
		if (strtotime($this->data['BotKoppeling']['einddatum']) < strtotime($this->data['BotKoppeling']['startdatum'])) {
			return false;
		}
		
		return true;
	}
}
